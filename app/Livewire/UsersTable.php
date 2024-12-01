<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component {
    use WithPagination;

    public int $perPage = 5;
    public string $search = '';
    public $admin = '';

    public function render() {
        return view('livewire.users-table', [
            'users' => User::search($this->search)->when($this->admin !== '', function($query) {
                $query->whereIsAdmin($this->admin);
            })->paginate($this->perPage)
        ]);
    }
}
