@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <h2 class="text-center text-3xl font-extrabold text-gray-900 mb-6">Verify Email Address</h2>

        @if (session('resent'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-md p-4">
                <p class="text-sm text-green-700">A fresh verification link has been sent to your email address.</p>
            </div>
        @endif

        <p class="text-center text-sm text-gray-600 mb-6">
            Before proceeding, please check your email for a verification link.
            If you did not receive the email, click the button below to request another.
        </p>

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-6">
            @csrf

            <div>
                <button
                    type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Resend Verification Email
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button
                    type="submit"
                    class="text-sm font-medium text-blue-600 hover:text-blue-500"
                >
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
