<?php

class TEST_API_USERTest
{
    private $baseUrl = 'http://localhost/CDAW/BackEnd/TP_Crud_REST/API_USER.php';
    
    public static function assertEquals($expected, $actual, $message = '')
    {
        if ($expected === $actual) {
            echo "\033[32mTest passed : $message\033[0m\n"; // Green color for passed tests
        } else {
            echo "\033[31mTest failed : $message\033[0m\n"; // Red color for failed tests
        }
    }
    public function testGetUserById()
    {
        $id = 1;
        $url = $this->baseUrl . '?id=' . $id;
        $response = file_get_contents($url);
        $user = json_decode($response);
        $this->assertEquals($id, $user->id , 'Get user by id');
    }

    public function testGetUserByName()
    {
        $name = 'John';
        $url = $this->baseUrl . '?name=' . $name;
        $response = file_get_contents($url);
        $user = json_decode($response);
        $this->assertEquals($name, $user[0]->name , 'Get user by name');
    }

    public function testGetUserByEmail()
    {
        $email = 'test@test.test';
        $url = $this->baseUrl . '?email=' . $email;
        $response = file_get_contents($url);
        $user = json_decode($response);
        $this->assertEquals($email, $user[0]->email , 'Get user by email');
    }

    public function testGetAllUsers()
    {
        $response = file_get_contents($this->baseUrl);
        $users = json_decode($response);
        $this->assertEquals(1, $users[0]->id , 'Get all users');
    }

    public function testCreateUser()
    {
        $data = ['name' => 'John', 'email' => 'test.test@test'];
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($data)
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($this->baseUrl, false, $context);
        $user = json_decode($response);
        $this->assertEquals($data['name'], $user->name , 'Create user');
        $this->assertEquals($data['email'], $user->email , 'Create user');
    }

    public function testUpdateUser($id)
    {
        $data = ['id' => $id, 'name' => 'John', 'email' => 'test2@test2.fr'];
        $options = [
            'http' => [
                'method' => 'PUT',
                'header' => 'Content-type: application/json',
                'content' => json_encode($data)
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($this->baseUrl, false, $context);
        $user = json_decode($response);
        $this->assertEquals($data['name'], $user->name , 'Update user');
        $this->assertEquals($data['email'], $user->email , 'Update user');
    }

    public function testDeleteUser($id)
    {
        $data = ['id' => $id];
        $options = [
            'http' => [
                'method' => 'DELETE',
                'header' => 'Content-type: application/json',
                'content' => json_encode($data)
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($this->baseUrl, false, $context);
        $user = json_decode($response);
        $this->assertEquals($data['id'], $user->id , 'Delete user');
    }

    public function run()
    {
        $this->testGetUserById();
        $this->testGetUserByName();
        $this->testGetUserByEmail();
        $this->testGetAllUsers();
        $this->testCreateUser();
        $this->testUpdateUser(1);
        $this->testDeleteUser(1);
    }
}

?>