<?php

namespace Tests\Browser\Pages\Users;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\UserInfo;

class CreateUserTest extends DuskTestCase
{
    use DatabaseMigrations;
    const NUMBER_RECORD_CREATE = 5;
    const NUMBER_RECORD_FIND = 5;

    public function setUp()
    {
        parent::setUp();
        factory(User::class, self::NUMBER_RECORD_CREATE)->create();
        factory(UserInfo::class, self::NUMBER_RECORD_CREATE)->create();
    }

    /**
     * Test url create user
     *
     * @return void
     */
    public function testCreateUserUrl()
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/admin/user/create')
                    ->assertPathIs('/admin/user/create')
                    ->assertSee('Create Form');
        });
    }
    public function testValidate()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('admin/user/create')
                ->type('name', '')
                ->type('email', '')
                ->type('password', '');
            $browser->press('Submit')
                ->pause(1000)
                ->assertSee('The name field is required.')
                ->assertSee('The email field is required.')
                ->assertSee('The password field is required.');
                });
    }
    /**
    * Test Users Create Success.
    *
    * @return void
    */
    public function testCreateUserSuccess()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit('/admin/user/create')
            ->type('name','Big')
            ->type('email','big@gmail.com')
            ->type('password','123123123');
            $browser->press('Submit')
            ->assertPathIs('/admin/user')
            ->assertSee('Successfully created the user!');
            $this->assertDatabaseHas('users', ['name' => 'Big']);
            });
    }
}