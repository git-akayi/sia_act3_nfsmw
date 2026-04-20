@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Customer Details: {{ $customer->name }}</span>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                </div>

                <div class="card-body">
                    <p><strong>ID:</strong> {{ $customer->id }}</p>
                    <p><strong>Name:</strong> {{ $customer->name }}</p>
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                    <p><strong>Account Created:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
                </div>

                <div class="card-footer">
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Edit Customer</a>
                    
                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection