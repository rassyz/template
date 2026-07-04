{{-- Enhanced comment section with reply functionality --}}
<section id="comment-section" class="bg-[#F9F9FC] mt-[50px] py-8">
    <div class="container mx-auto px-4 max-w-[1130px]">
        <h2 class="text-2xl font-bold mb-4">Comments</h2>
        <div class="space-y-4">
            @forelse($articleNews->comments->where('parent_id', null) as $comment)
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center mb-2">
                        <img src="{{ asset('assets/images/photos/avatar.png') }}" alt="User Avatar" class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <h3 class="font-semibold">{{ $comment->name }}</h3>
                            <p class="text-sm text-gray-500">Posted on {{ $comment->created_at->format('H:i, d M Y') }}</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-3">
                        {{ $comment->review }}
                    </p>
                    <div class="flex items-center mb-3">
                        <button class="text-blue-500 hover:text-blue-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                            </svg>
                            Like
                        </button>
                        <button class="text-gray-500 hover:text-gray-600 reply-btn" data-comment-id="{{ $comment->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414L2.586 8l3.707-3.707a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Reply
                        </button>
                    </div>

                    {{-- Reply Form (Initially Hidden) --}}
                    <div id="reply-form-{{ $comment->id }}" class="reply-form hidden mt-4 p-3 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                        <form action="{{ route('front.comment.reply', $comment->id) }}" method="POST">
                            @csrf
                            <h4 class="text-md font-semibold mb-3 text-gray-700">Reply to {{ $comment->name }}</h4>
                            <div class="mb-3">
                                <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                                <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="mb-3">
                                <label for="reply" class="block text-gray-700 font-medium mb-2">Reply</label>
                                <textarea id="reply" name="reply" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
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
                    @if($comment->replies && $comment->replies->count() > 0)
                        <div class="replies mt-4 ml-8 space-y-3">
                            @foreach($comment->replies as $reply)
                                <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-gray-300">
                                    <div class="flex items-center mb-2">
                                        <img src="{{ asset('assets/images/photos/avatar.png') }}" alt="User Avatar" class="w-8 h-8 rounded-full mr-2">
                                        <div>
                                            <h4 class="font-semibold text-sm">{{ $reply->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $reply->created_at->format('H:i, d M Y') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 text-sm">
                                        {{ $reply->review }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p>No comments yet.</p>
            @endforelse
        </div>

        <!-- Alert Section -->
        @if (session('success'))
            <div id="alert-success" class="mt-4 mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div id="alert-error" class="mt-4 mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error occurred:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Add Comment Form -->
        <form action="{{ route('front.comment', $articleNews->id) }}" method="POST" class="mt-8 bg-white p-6 rounded-lg shadow">
            @csrf
            <h3 class="text-lg font-semibold mb-4">Add a Comment</h3>
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="comment" class="block text-gray-700 font-medium mb-2">Comment</label>
                <textarea id="comment" name="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Post Comment
            </button>
        </form>
    </div>
</section>

{{-- JavaScript for Reply Functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle Reply Button Clicks
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);

            // Hide all other reply forms
            document.querySelectorAll('.reply-form').forEach(form => {
                if (form.id !== `reply-form-${commentId}`) {
                    form.classList.add('hidden');
                }
            });

            // Toggle current reply form
            replyForm.classList.toggle('hidden');

            // Focus on name input when form is shown
            if (!replyForm.classList.contains('hidden')) {
                const nameInput = replyForm.querySelector(`#reply-name-${commentId}`);
                nameInput.focus();
            }
        });
    });

    // Handle Cancel Reply Buttons
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.classList.add('hidden');

            // Clear form fields
            const form = replyForm.querySelector('form');
            form.reset();
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('#alert-success, #alert-error');
        alerts.forEach(alert => {
            if (alert) {
                alert.style.display = 'none';
            }
        });
    }, 5000);
});
</script>
