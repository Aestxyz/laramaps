<?php

use function Laravel\Folio\name;
use function Livewire\Volt\{state, on, rules};
use App\Models\shop;
use App\Models\Transaction;

name('product-detail');

state([
    'user_id' => fn() => auth()->user()->id ?? null,
    'product_id' => fn() => $this->product->id,
    'condition' => false, // ganti false
    'duration' => 1,
    'with_driver' => false,
    'description',
    'rent_date',
    'product',
    'total',
    'price_product',
]);

rules([
    'user_id' => ['required', 'exists:users,id'],
    'product_id' => ['required', 'exists:products,id'],
    'condition' => ['required', 'boolean'],
    'rent_date' => ['required', 'date', 'after:today'],
    'duration' => ['required', 'integer', 'min:1', 'max:30'],
    'with_driver' => ['required', 'boolean'],
]);

on([
    'toggleCondition' => function () {
        $this->condition = $this->condition;
    },
]);

$turnOnCondition = function () {
    $this->condition = true;
    $this->dispatch('toggleCondition');
};

$turnOffCondition = function () {
    $this->condition = false;
    $this->dispatch('toggleCondition');
};

$increment = fn() => $this->duration++;
$decrement = fn() => $this->duration--;

$calculateSubTotal = function () {
    $total = 0;

    if ($this->with_driver == 1) {
        $driver = 200000;
    } else {
        $driver = 0;
    }

    $subTotal = $this->product->price * $this->duration;
    $total = $subTotal + $driver;
    return $total;
};

$rentproduct = function () {
    if (Auth::check()) {
        $validate = $this->validate();

        $additionalData = [
            'price_product' => $this->product->price,
            'price_driver' => $this->with_driver ? 200000 : 0,
            'subtotal' => $this->calculateSubTotal(),
        ];

        Transaction::create(array_merge($validate, $additionalData));
        $this->redirectRoute('succesfully');
    } else {
        $this->redirect('/login');
    }
};
?>

<x-guest-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>


    @volt
        <div>
            <section class="pb-5">
                <div class="container-fluid">
                    <div class="mb-3 d-flex justify-content-center">
                        <a data-fslightbox="mygalley" class="rounded-4" target="_blank" data-type="image"
                            href="{{ Storage::url($product->imageProducts->first()->image_path) }}">
                            <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="img-fluid"
                                src="{{ Storage::url($product->imageProducts->first()->image_path) }}" />
                        </a>
                    </div>

                    <div class="row gx-5">
                        <div class="col-lg-6">
                            <div class="ps-lg-3 text-break">
                                <small class="fw-bold" style="color: #f35525;">{{ $product->category->name }}</small>
                                <h1 class="title text-dark fw-bold">
                                    {{ $product->name }}
                                </h1>

                                <div class="my-3">
                                    <span class="h5 fw-bold">
                                        {{ 'Rp. ' . Number::format($product->price, locale: 'id') }}
                                    </span>
                                </div>

                                <div class="row">
                                    <dt class="col-5 mb-2">
                                        Transmisi
                                    </dt>
                                    <dd class="col-7 mb-2">
                                        {{ $product->transmission }}
                                    </dd>

                                    <dt class="col-5 mb-2">
                                        Kursi
                                    </dt>
                                    <dd class="col-7 mb-2">
                                        {{ $product->capacity }}
                                    </dd>

                                    <dt class="col-5 mb-2">
                                        Bagasi
                                    </dt>
                                    <dd class="col-7 mb-2">
                                        {{ $product->space }} Koper
                                    </dd>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3" style="overflow-wrap: anywhere;">
                                {!! $product->description !!}
                            </div>
                        </div>

                    </div>

                    <section>
                        @if ($condition == false)
                            <!-- Tombol untuk mengaktifkan status -->
                            <div class="d-grid mb-5">
                                <button wire:click="turnOnCondition" class="btn btn-primary rounded">
                                    Rental Mobil Ini
                                </button>
                            </div>
                        @else
                            <div class="d-grid mb-5">
                                <button wire:click="turnOffCondition" class="btn btn-danger rounded">
                                    Batal Rental
                                </button>
                            </div>
                            @include('pages.guest.product.form-rent')
                        @endif
                    </section>
                </div>
            </section>

        </div>
    @endvolt
</x-guest-layout>