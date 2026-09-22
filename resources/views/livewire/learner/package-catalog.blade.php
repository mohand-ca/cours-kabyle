<div>
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">{{ __('learner.packages.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('learner.packages.subtitle') }}</p>
    </div>

    @if(session('message'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('message') }}
        </div>
    @endif

    @if($sessionsRemaining > 0)
        <div class="mb-6 rounded-xl border border-violet-100 bg-violet-50 px-5 py-4">
            <p class="text-sm font-medium text-violet-800">
                {{ trans_choice('learner.packages.remaining_sessions', $sessionsRemaining, ['count' => $sessionsRemaining]) }}
            </p>
        </div>
    @endif

    <div class="grid grid-cols-3 gap-5">
        @foreach($packages as $package)
            <div class="flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm hover:border-violet-200 hover:shadow-md transition-all">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $package->name }}</h3>
                    <p class="mt-1 text-3xl font-bold text-violet-600">
                        ${{ $package->priceInDollars() }}
                    </p>
                    <p class="mt-0.5 text-xs text-gray-400">
                        ${{ number_format($package->price_cents / $package->sessions_count / 100, 2) }} {{ __('learner.packages.per_session') }}
                    </p>
                    <p class="mt-4 text-sm text-gray-600">
                        {{ trans_choice('learner.packages.sessions', $package->sessions_count, ['count' => $package->sessions_count]) }}
                    </p>
                </div>

                <a href="{{ route('learner.checkout', $package) }}"
                    class="mt-6 block rounded-xl bg-violet-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-violet-700 transition-colors">
                    {{ __('learner.packages.buy') }}
                </a>
            </div>
        @endforeach
    </div>
</div>
