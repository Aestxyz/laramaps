<?php
use function Laravel\Folio\name;
use function Livewire\Volt\{state, rules, uses};
use App\Models\User;
use Jantinnerezo\LivewireAlert\LivewireAlert;

uses([LivewireAlert::class]);

name('admin.create');

state(['name', 'email', 'password', 'phone_number']);

rules([
    'name' => 'required|min:5',
    'email' => 'required|min:5|unique:users,email',
    'password' => 'required|min:5',
    'phone_number' => 'required|unique:users,phone_number,id|digits_between:11,12',
]);

$store = function () {
    $validateData = $this->validate();
    $validateData['role'] = 'admin';
    User::create($validateData);

    $this->reset('name', 'email', 'password', 'phone_number');

    $this->redirectRoute('admin.index');

    $this->flash(
        'success',
        'Proses Berhasil',
        [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
            'text' => '',
        ],
        '/superusers/admins',
    );
};

?>
<x-admin-layout>
    <x-slot name="title">Tambah Admin</x-slot>

    @volt
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Beranda</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">Data Admin</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Data Admin</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header">
                    <div class="alert alert-primary" role="alert">
                        <strong>Tambah Admin</strong>
                        <p>Pada halaman tambah pengguna, Anda dapat memasukkan informasi pengguna baru, seperti nama, alamat
                            email,
                            kata sandi, dan peran pengguna (admin)
                        </p>
                    </div>
                </div>

                <div class="card-body">
                    <form wire:submit="store">
                        @csrf
                        <div class="row">
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        wire:model="name" id="name" aria-describedby="nameId"
                                        placeholder="Enter admin name" autofocus autocomplete="name" />
                                    @error('name')
                                        <small id="nameId" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        wire:model="email" id="email" aria-describedby="emailId"
                                        placeholder="Enter admin email" />
                                    @error('email')
                                        <small id="emailId" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">No. Telp</label>
                                    <input type="number" class="form-control @error('phone_number') is-invalid @enderror"
                                        wire:model="phone_number" id="phone_number" aria-describedby="phone_numberId"
                                        placeholder="Enter admin phone_number" />
                                    @error('phone_number')
                                        <small id="phone_numberId" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Kata Sandi</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            wire:model="password" id="password" aria-describedby="passwordId"
                                            placeholder="Enter admin password" />
                                        <button type="button" class="btn btn-light border">
                                            <i class="bi bi-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <small id="passwordId" class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endvolt

</x-admin-layout>
