@extends('frontend.master')
@section('content')

<body class="font-[Poppins] pb-[83px]">
    <x-navbar />
	<nav id="Category" class="max-w-[1130px] mx-auto flex justify-center items-center gap-4 mt-[30px] flex-wrap">

        @foreach($categories as $item_category)
            <a href="{{ route('front.category', $item_category->slug) }}" class="rounded-full p-[8px_16px] flex gap-[8px] font-semibold transition-all duration-300 border border-[#EEF0F7] hover:ring-2 hover:ring-[#FF6B18] mb-0 sm:mb-0">
                <div class="w-5 h-5 flex shrink-0">
                    <img src="{{ Storage::url($item_category->icon) }}" alt="icon" />
                </div>
                <span class="text-xs sm:text-sm">{{ $item_category->name }}</span>
            </a>
        @endforeach

    </nav>

	<section id="Category-result" class="max-w-[1130px] mx-auto flex items-center flex-col gap-[30px] mt-[70px]">
        <h1 class="text-4xl leading-[45px] font-bold text-center">
            Explore Our <br />
            {{ $category->name }} News
        </h1>

        <div id="search-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 lg:gap-[30px] w-full">

            @forelse($category->news()->latest()->get() as $news_category)
                <a href="{{ route('front.details', $news_category->slug) }}" class="card">
                    <div class="mx-4 sm:mx-0 flex flex-col gap-4 p-[20px] sm:p-[26px] transition-all duration-300 ring-1 ring-[#EEF0F7] hover:ring-2 hover:ring-[#FF6B18] rounded-[20px] overflow-hidden bg-white">
                        <div class="thumbnail-container h-[200px] relative rounded-[20px] overflow-hidden">
                            <div class="badge absolute left-5 top-5 bottom-auto right-auto flex p-[8px_18px] bg-white rounded-[50px]">
                                <p class="text-xs leading-[18px] font-bold uppercase">{{ $news_category->category->name }}</p>
                            </div>
                            <img src="{{ Storage::url($news_category->thumbnail) }}" alt="thumbnail photo" class="w-full h-full object-cover" />
                        </div>
                        <div class="flex flex-col gap-[6px]">
                            <h3 class="text-lg leading-[27px] font-bold">
                                {{ substr($news_category->name, 0, 50) }}{{ strlen($news_category->name) > 50 ? '...' : '' }}
                            </h3>
                            <p class="text-sm leading-[21px] text-[#A3A6AE]">
                                {{ $news_category->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <p>No articles Found</p>
            @endforelse

        </div>
    </section>

	<section id="Advertisement" class="max-w-[1130px] mx-auto flex justify-center mt-[70px] px-4">

        <div class="flex flex-col gap-3 w-full sm:w-fit">
            <a href="{{ $bannerads->link }}">
                <div class="w-full sm:w-[900px] h-[120px] flex rounded-2xl overflow-hidden border border-[#EEF0F7]">
                    <img src="{{ Storage::url($bannerads->thumbnail) }}" class="object-cover w-full h-full" alt="ads" />
                </div>
            </a>
            <p class="font-medium text-sm leading-[21px] text-[#A3A6AE] flex gap-1 text-center sm:text-left">
                Our Advertisement
                <a href="#" class="w-[18px] h-[18px]">
                    <img src="{{ asset('assets/images/icons/message-question.svg') }}" alt="icon" />
                </a>
            </p>
        </div>

    </section>

</body>

@endsection
