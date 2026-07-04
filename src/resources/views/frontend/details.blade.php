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
	<header class="flex flex-col items-center gap-[50px] mt-[70px] px-4">

        <!-- Headline Section -->
        <div id="Headline" class="max-w-[1130px] mx-auto flex flex-col gap-4 items-center">
            <p class="w-fit text-[#A3A6AE]">{{ $articleNews->created_at->diffForHumans() }} • {{ $articleNews->category->name }}</p>
            <h1 class="text-[36px] sm:text-[46px] font-bold leading-[1.3] text-center tracking-normal [font-feature-settings:'liga'_0]">
                {{ $articleNews->name }}
            </h1>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-5 sm:gap-[70px]">
                <!-- Author Section -->
                <a id="Author" href="{{ route('front.author', $articleNews->author->slug) }}" class="w-fit h-fit">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden">
                            <img src="{{ Storage::url($articleNews->author->avatar) }}" class="object-cover w-full h-full" alt="avatar">
                        </div>
                        <div class="flex flex-col">
                            <p class="font-semibold text-sm leading-[21px]">{{ $articleNews->author->name }}</p>
                            <p class="text-xs leading-[18px] text-[#A3A6AE]">{{ $articleNews->author->occupation }}</p>
                        </div>
                    </div>
                </a>

                <!-- Rating Section -->
                <div id="Rating" class="flex items-center gap-1">
                    <div class="flex items-center">
                        <div class="w-4 h-4 flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" alt="star">
                        </div>
                        <div class="w-4 h-4 flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" alt="star">
                        </div>
                        <div class="w-4 h-4 flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" alt="star">
                        </div>
                        <div class="w-4 h-4 flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" alt="star">
                        </div>
                        <div class="w-4 h-4 flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" alt="star">
                        </div>
                    </div>
                    <p class="font-semibold text-xs leading-[18px]">(12,490)</p>
                </div>
            </div>
        </div>

        <!-- Article Thumbnail Section -->
        <div class="w-full h-[300px] sm:h-[500px] flex shrink-0 overflow-hidden">
            <img src="{{ Storage::url($articleNews->thumbnail) }}" class="object-cover w-full h-full" alt="cover thumbnail">
        </div>

    </header>

	<section id="Article-container" class="max-w-[1130px] mx-auto flex flex-col sm:flex-row gap-10 mt-[50px] px-4">

        <!-- Article Content -->
        <article id="Content-wrapper" class="flex-1">
            {!! $articleNews->content !!}
        </article>

        <!-- Sidebar Section -->
        <div class="side-bar flex flex-col w-full sm:w-[300px] gap-10">

            <!-- Advertisement 1 -->
            <div class="ads flex flex-col gap-3 w-full">
                <a href="{{ $squareads_1->link }}">
                    <img src="{{ Storage::url($squareads_1->thumbnail) }}" class="object-contain w-full h-full" alt="ads" />
                </a>
                <p class="font-medium text-sm leading-[21px] text-[#A3A6AE] flex gap-1">
                    Our Advertisement <a href="#" class="w-[18px] h-[18px]"><img src="{{ asset('assets/images/icons/message-question.svg') }}" alt="icon" /></a>
                </p>
            </div>

            <!-- More From Author Section -->
            <div id="More-from-author" class="flex flex-col gap-4">
                <p class="font-bold">More From Author</p>
                @forelse($author_news as $item_news)
                    <a href="{{ route('front.details', $item_news->slug) }}" class="card-from-author">
                        <div class="rounded-[20px] ring-1 ring-[#EEF0F7] p-[14px] flex gap-4 hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300">
                            <div class="w-[70px] h-[70px] flex shrink-0 overflow-hidden rounded-2xl">
                                <img src="{{ Storage::url($item_news->thumbnail) }}" class="object-cover w-full h-full" alt="thumbnail">
                            </div>
                            <div class="flex flex-col gap-[6px]">
                                <p class="line-clamp-2 font-bold">{{ $item_news->name }}</p>
                                <p class="text-xs leading-[18px] text-[#A3A6AE]">{{ $item_news->created_at->diffForHumans() }} • {{ $item_news->category->name }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p>No articles found</p>
                @endforelse
            </div>

            <!-- Advertisement 2 -->
            <div class="ads flex flex-col gap-3 w-full">
                <a href="{{ $squareads_2->link }}">
                    <img src="{{ Storage::url($squareads_2->thumbnail) }}" class="object-contain w-full h-full" alt="ads" />
                </a>
                <p class="font-medium text-sm leading-[21px] text-[#A3A6AE] flex gap-1">
                    Our Advertisement <a href="#" class="w-[18px] h-[18px]"><img src="{{ asset('assets/images/icons/message-question.svg') }}" alt="icon" /></a>
                </p>
            </div>

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

    {{-- comment --}}
    <section id="comment-section" class="bg-[#F9F9FC] mt-[50px] py-8">
        <div class="container mx-auto px-4 max-w-[1130px]">
            <h2 class="text-2xl font-bold mb-4">Comments ({{ $articleNews->comments->count() }})</h2>

            {{-- Alert Section --}}
            @if (session('success'))
                <div id="alert-success" class="mt-2 mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div id="alert-error" class="mt-2 mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Terjadi kesalahan:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                @forelse($articleNews->comments as $comment)
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center mb-2">
                            <img src="{{ asset('assets/images/photos/avatar.png') }}" alt="User Avatar" class="w-10 h-10 rounded-full mr-3">
                            <div>
                                <h3 class="font-semibold">{{ $comment->name }}</h3>
                                <p class="text-sm text-gray-500">Posted on {{ $comment->created_at->format('H:i, d M Y') }}</p>
                            </div>
                        </div>
                        <p class="text-gray-700">{{ $comment->review }}</p>

                        <div class="flex items-center mt-2">
                            <button class="text-gray-500 hover:text-gray-600 reply-btn" data-comment-id="{{ $comment->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414L2.586 8l3.707-3.707a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Reply
                            </button>
                        </div>

                        {{-- Reply Form --}}
                        <div id="reply-form-{{ $comment->id }}" class="reply-form hidden mt-4 p-3 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                            <form action="{{ route('front.reply', $comment->id) }}" method="POST">
                                @csrf
                                <h4 class="text-md font-semibold mb-3 text-gray-700">Reply to {{ $comment->name }}</h4>

                                <div class="mb-3">
                                    <label for="reply-name-{{ $comment->id }}" class="block text-gray-700 font-medium mb-2">Name</label>
                                    <input type="text" id="reply-name-{{ $comment->id }}" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="reply-comment-{{ $comment->id }}" class="block text-gray-700 font-medium mb-2">Reply</label>
                                    <textarea id="reply-comment-{{ $comment->id }}" name="reply" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('reply') }}</textarea>
                                    @error('reply')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex gap-2">
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Post Reply
                                    </button>
                                    <button type="button" class="cancel-reply bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400" data-comment-id="{{ $comment->id }}">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Display Replies --}}
                        @if($comment->replyComments && $comment->replyComments->count() > 0)
                            <div class="replies mt-4 ml-8 space-y-3">
                                @foreach($comment->replyComments as $reply)
                                    <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-gray-300">
                                        <div class="flex items-center mb-2">
                                            <img src="{{ asset('assets/images/photos/avatar.png') }}" alt="User Avatar" class="w-8 h-8 rounded-full mr-2">
                                            <div>
                                                <h4 class="font-semibold text-sm">{{ $reply->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $reply->created_at->format('H:i, d M Y') }}</p>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 text-sm">{{ $reply->review }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p>No comments yet.</p>
                @endforelse
            </div>

            {{-- Add Comment Form --}}
            <form action="{{ route('front.comment', $articleNews->id) }}" method="POST" class="mt-8 bg-white p-4 rounded-lg shadow">
                @csrf
                <h3 class="text-lg font-semibold mb-2">Add a Comment</h3>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="comment" class="block text-gray-700 font-medium mb-2">Comment</label>
                    <textarea id="comment" name="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Post Comment
                </button>
            </form>
        </div>
    </section>


	<section id="Up-to-date" class="w-full flex justify-center py-[50px] bg-[#F9F9FC]">
		<div class="max-w-[1130px] mx-auto flex flex-col gap-[30px]">
			<div class="ml-4 sm:ml-0 flex justify-between items-center">
				<h2 class="font-bold text-[26px] leading-[39px]">
					Other News You <br />
					Might Be Interested
				</h2>
			</div>
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-[30px]">

                @forelse($articles as $article)
                    <a href="{{ route('front.details', $article->slug) }}" class="card-news">
                        <div class="mx-4 sm:mx-0 rounded-[20px] ring-1 ring-[#EEF0F7] p-[20px] sm:p-[26px] flex flex-col gap-4 hover:ring-2 hover:ring-[#FF6B18] transition-all duration-300 bg-white">
                            <div class="thumbnail-container w-full h-[200px] rounded-[20px] flex shrink-0 overflow-hidden relative">
                                <p class="badge-white absolute top-5 left-5 rounded-full p-[8px_18px] bg-white font-bold text-xs leading-[18px]">
                                    {{ $article->category->name }}
                                </p>
                                <img src="{{ Storage::url($article->thumbnail) }}" class="object-cover w-full h-full" alt="thumbnail" />
                            </div>
                            <div class="card-info flex flex-col gap-[6px]">
                                <h3 class="font-bold text-lg leading-[27px]">
                                    {{ substr($article->name, 0, 50) }}{{ strlen($article->name) > 50 ? '...' : '' }}
                                </h3>
                                <p class="text-sm leading-[21px] text-[#A3A6AE]">
                                    {{ $article->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p>Article not Found</p>
                @endforelse

            </div>

		</div>
	</section>


</body>

@endsection

@push('after-style')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets/js/two-lines-text.js') }}"></script>
    <!-- Auto Dismiss Alert (after 5 seconds) -->
        <script>
            setTimeout(() => {
                const successAlert = document.getElementById('alert-success');
                const errorAlert = document.getElementById('alert-error');
                if (successAlert) successAlert.style.display = 'none';
                if (errorAlert) errorAlert.style.display = 'none';
            }, 5000); // 5000 ms = 5 detik
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle Reply Button Clicks
                document.querySelectorAll('.reply-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.getAttribute('data-comment-id');
                        const replyForm = document.getElementById(`reply-form-${commentId}`);

                        if (!replyForm) return;

                        // Hide all other reply forms
                        document.querySelectorAll('.reply-form').forEach(form => {
                            if (form !== replyForm) {
                                form.classList.add('hidden');
                            }
                        });

                        // Toggle current reply form
                        replyForm.classList.toggle('hidden');

                        // Focus on name input when form is shown
                        if (!replyForm.classList.contains('hidden')) {
                            const nameInput = document.getElementById(`reply-name-${commentId}`);
                            if (nameInput) {
                                nameInput.focus();
                            }
                        }
                    });
                });

                // Handle Cancel Reply Buttons
                document.querySelectorAll('.cancel-reply').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.getAttribute('data-comment-id');
                        const replyForm = document.getElementById(`reply-form-${commentId}`);
                        if (!replyForm) return;

                        replyForm.classList.add('hidden');

                        const form = replyForm.querySelector('form');
                        if (form) form.reset();
                    });
                });

                // Auto-hide alerts after 5 seconds
                setTimeout(function() {
                    const alerts = document.querySelectorAll('#alert-success, #alert-error');
                    alerts.forEach(alert => {
                        if (alert) {
                            alert.style.transition = 'opacity 0.5s ease';
                            alert.style.opacity = '0';
                            setTimeout(() => alert.style.display = 'none', 500);
                        }
                    });
                }, 5000);
            });
        </script>



@endpush
