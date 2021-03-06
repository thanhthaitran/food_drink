<?php

namespace Tests\Browser\Pages\Backend\User;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\UserInfo;

class EditUserTest extends DuskTestCase
{
    use DatabaseMigrations;
    const NUMBER_RECORD_CREATE_ADMIN = 5;
    const NUMBER_RECORD_CREATE_USER = 6;
    const NUMBER_RECORD_FIND = 5;

    public function setUp()
    {
        parent::setUp();
        factory('App\User', 'admin', 1)->create();
        factory(User::class, self::NUMBER_RECORD_CREATE_ADMIN)->create();
        factory(UserInfo::class, self::NUMBER_RECORD_CREATE_USER)->create();
    }

    /**
     * Test Users Edit Success.
     *
     * @return void
     */
    public function testEditUser()
    {
        $user = User::first();
        $userInfo = $user->load('userInfo');
        $this->browse(function (Browser $browser) use ($user, $userInfo) {
            $browser->loginAs(User::find(1))
                    ->visit('admin/user/'.$user->id.'/edit')
                    ->assertSee('Edit Form', $user->name, $userInfo->userInfo->phone, $userInfo->userInfo->address);
        });
    }

    

    /**
    * Test Users Edit Success.
    *
    * @return void
    */
    public function testEditUserSuccess()
    {
        $user = User::first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/user/'.$user->id.'/edit')
                    ->type('name', 'test name')
                    ->press('submit')
                    ->assertPathIs('/admin/user')
                    ->assertSee('Successfully updated the user');
            $this->assertDatabaseHas('users', ['name' => 'test name']);
        });
    }

    /**
     * Test Validation Update User.
     *
     * @return void
     */
    public function testValidationUpdateUser()
    {
    $user = User::first();
    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs(User::find(1))
                ->visit('/admin/user/'.$user->id.'/edit')
                ->type('name', '')
                ->type('phone', '(873) 396-4030(873) 396-4030(873) 396-4030(873) 396-4030(873) 396-4030(873) 396-4030')
                ->type('address', '4361 Jayce Summit Apt. 286North Mariobury')
                ->press('submit')
                ->assertPathIs('/admin/user/'.$user->id.'/edit')
                ->assertSee('The name field is required.')
                ->assertSee('The phone may not be greater than 50 characters.');
        });
    }

    /**
    * Test 404 Page Not found when click edit user.
    *
    * @return void
    */
    public function test404PageForClickEdit()
    {
        $user = User::find(self::NUMBER_RECORD_FIND);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/user')
                    ->assertSee('List Users');
            $user->delete();
            $browser->press('tbody tr:nth-child(6) td:nth-child(6) a:first-child');
            $browser->assertSee('Can not find user with corresponding id.');
        });
    }

    /**
    * Test 404 Page Not found when click submit edit user.
    *
    * @return void
    */
    public function test404PageForClickSubmit()
    {
        $user = User::find(self::NUMBER_RECORD_FIND);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs(User::find(1))
                    ->visit('/admin/user')
                    ->assertSee('List User')
                    ->press('tbody tr:nth-child(6) td:nth-child(6) a:first-child')
                    ->assertPathIs('/admin/user/'.$user->id.'/edit')
                    ->assertSee('Edit Form User')
                    ->type('name','aaaaa');
            $user->delete();
            $browser->press('submit')
                    ->assertSee('Can not find user with corresponding id.');
        });
    }
}
