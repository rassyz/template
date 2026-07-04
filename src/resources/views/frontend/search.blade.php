@extends('frontend.master')
@section('content')

	<body class="font-[Poppins]">
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
		<section id="heading" class="max-w-[1130px] mx-auto flex items-center flex-col gap-[30px] mt-[70px] px-4">

            <h1 class="text-4xl leading-[45px] font-bold text-center">
                Explore Hot Trending <br />
                Good News Today
            </h1>

            <form method="GET" action="{{ route('front.search') }}">
                <label for="search-bar" class="w-full sm:w-[500px] flex p-[12px_20px] transition-all duration-300 gap-[10px] ring-1 ring-[#E8EBF4] focus-within:ring-2 focus-within:ring-[#FF6B18] rounded-[50px] group">
                    @csrf
                    <div class="w-5 h-5 flex shrink-0">
                        <img src="{{ asset('assets/images/icons/search-normal.svg') }}" alt="icon" />
                    </div>
                    <input
                        autocomplete="off"
                        type="text"
                        id="search-bar"
                        name="keyword"
                        placeholder="Search hot trendy news today..."
                        class="appearance-none font-semibold placeholder:font-normal placeholder:text-[#A3A6AE] outline-none focus:ring-0 w-full"
                    />
                </label>
            </form>
        </section>

		<section id="search-result" class="max-w-[1130px] mx-auto flex items-start flex-col gap-[30px] mt-[70px] mb-[100px] px-4">

            <h2 class="text-[26px] leading-[39px] font-bold">
                Search Result: <span>{{ Str::ucfirst($keyword) }}</span>
            </h2>

            <div id="search-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-[30px] w-full">

                @forelse($articles as $news_article)
                    <a href="{{ route('front.details', $news_article->slug) }}" class="card">
                        <div class="flex flex-col gap-4 p-[20px] sm:p-[26px] transition-all duration-300 ring-1 ring-[#EEF0F7] hover:ring-2 hover:ring-[#FF6B18] rounded-[20px] overflow-hidden bg-white">
                            <div class="thumbnail-container h-[200px] relative rounded-[20px] overflow-hidden">
                                <div class="badge absolute left-5 top-5 bottom-auto right-auto flex p-[8px_18px] bg-white rounded-[50px]">
                                    <p class="text-xs leading-[18px] font-bold uppercase">{{ $news_article->category->name }}</p>
                                </div>
                                <img src="{{ Storage::url($news_article->thumbnail) }}" alt="thumbnail photo" class="w-full h-full object-cover" />
                            </div>
                            <div class="flex flex-col gap-[6px]">
                                <h3 class="text-lg leading-[27px] font-bold">
                                    {{ substr($news_article->name, 0, 50) }}{{ strlen($news_article->name) > 50 ? '...' : '' }}
                                </h3>
                                <p class="text-sm leading-[21px] text-[#A3A6AE]">
                                    {{ $news_article->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p>No articles Found</p>
                @endforelse

            </div>
        </section>

	</body>

@endsection
