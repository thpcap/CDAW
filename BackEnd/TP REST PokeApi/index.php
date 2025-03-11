<?php
    echo '<link rel="stylesheet" href="../../style.css">';
    echo '<h1>Berries</h1>';
    echo '<div id="berries">';
    function get_data_from_url($url) {
        $data = file_get_contents($url);
        return $data;
    }

    class Berry{
        const URL = 'https://pokeapi.co/api/v2/berry/';
        protected $id;
        public $firmness;
        public $flavors = [];
        public $growth_time;
        public $item;
        public $name;
        public $natural_gift_power;
        public $size;
        public $smoothness;
        public $soil_dryness;
        public $natural_gift_type;

        public function __construct($id){
            $this->id = $id;
            $data = json_decode(get_data_from_url(self::URL.$id), true);
            $this->name = $data['name'];
            $this->size = $data['size'];
            $this->firmness = $data['firmness']['name'];
            foreach ($data['flavors'] as $flavor) {
                $this->flavors[] = $flavor['flavor']['name'];
            }
            $this->growth_time = $data['growth_time'];
            $this->item = $data['item']['name'];
            $this->natural_gift_power = $data['natural_gift_power'];
            $this->smoothness = $data['smoothness'];
            $this->soil_dryness = $data['soil_dryness'];
            $this->natural_gift_type = $data['natural_gift_type']['name'];
        }

        public function __get($name){
            if (property_exists($this, $name)) {
                return $this->$name;
            }
        }

        public function __set($name, $value){
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }

        public static function keys_To_Html_Table_Row(){
            echo "
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Firmness</th>
                    <th>Flavors</th>
                    <th>Growth Time</th>
                    <th>Item</th>
                    <th>Natural Gift Power</th>
                    <th>Smoothness</th>
                    <th>Soil Dryness</th>
                    <th>Natural Gift Type</th>
                </tr>";
        }
        
        public function values_To_Html_Table_Row() {
            echo "
                <tr>
                    <td>".$this->id."</td>
                    <td>".$this->name."</td>
                    <td>".$this->size."</td>
                    <td>".$this->firmness."</td>
                    <td>".implode(', ', $this->flavors)."</td>
                    <td>".$this->growth_time."</td>
                    <td>".$this->item."</td>
                    <td>".$this->natural_gift_power."</td>
                    <td>".$this->smoothness."</td>
                    <td>".$this->soil_dryness."</td>
                    <td>".$this->natural_gift_type."</td>
                </tr>";
        }
    }

    function create_Html_Table(){
        echo "<table>";
        Berry::keys_To_Html_Table_Row();
        $nb_berry = json_decode(get_data_from_url(Berry::URL), true)['count'];
        for ($i = 1; $i <= $nb_berry; $i++) {
            $berry = new Berry($i);
            $berry->values_To_Html_Table_Row();
        }
        echo "</table>";
    }

    create_Html_Table();
    echo "</div>";
?>
