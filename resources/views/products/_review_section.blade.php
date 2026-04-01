{{--
    Partial: resources/views/products/_review_section.blade.php
    Include di products/show.blade.php dengan:
    @include('products._review_section', ['product' => $product, 'reviews' => $reviews, 'eligibleOrders' => $eligibleOrders])
--}}

<div class="mt-10 border-t border-gray-100 pt-8">

    {{-- ===== HEADER RATING SUMMARY ===== --}}
    <div class="flex items-center gap-6 mb-8">
        <div class="text-center">
            <div class="text-5xl font-black text-gray-900">
                {{ $reviews->count() > 0 ? number_format($reviews->avg('bintang'), 1) : '0.0' }}
            </div>
            <div class="flex justify-center gap-0.5 my-1">
                @php $avg = $reviews->avg('bintang') ?? 0; @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= round($avg) ? 'text-amber-400' : 'text-gray-200' }}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
            <div class="text-xs text-gray-400">{{ $reviews->count() }} ulasan</div>
        </div>

        {{-- Bar distribusi bintang --}}
        <div class="flex-1 space-y-1.5">
            @for ($star = 5; $star >= 1; $star--)
                @php $count = $reviews->where('bintang', $star)->count(); $pct = $reviews->count() > 0 ? ($count / $reviews->count()) * 100 : 0; @endphp
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 w-4 text-right">{{ $star }}</span>
                    <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-amber-400 h-2 rounded-full transition-all duration-500"
                            style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="text-xs text-gray-400 w-6">{{ $count }}</span>
                </div>
            @endfor
        </div>
    </div>

    {{-- ===== FORM TULIS REVIEW ===== --}}
    @auth
        @if (auth()->user()->role === 'pembeli')
            @if (isset($eligibleOrders) && $eligibleOrders->count() > 0)
                @php
                    // Cek apakah semua order eligible sudah direview
                    $sudahSemua = $eligibleOrders->every(fn($o) =>
                        $reviews->where('user_id', auth()->id())->where('order_id', $o->order_id)->count() > 0
                    );
                @endphp

                @if (!$sudahSemua)
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 mb-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Tulis Ulasan
                    </h3>

                    <form action="{{ route('reviews.store', $product->product_id) }}" method="POST">
                        @csrf

                        {{-- Pilih order jika lebih dari 1 --}}
                        @if ($eligibleOrders->count() > 1)
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Pesanan</label>
                            <select name="order_id" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm">
                                @foreach ($eligibleOrders as $ord)
                                    @if ($reviews->where('user_id', auth()->id())->where('order_id', $ord->order_id)->count() === 0)
                                    <option value="{{ $ord->order_id }}">
                                        Order #{{ $ord->order_id }} — {{ $ord->created_at->format('d M Y') }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @else
                            <input type="hidden" name="order_id" value="{{ $eligibleOrders->first()->order_id }}">
                        @endif

                        {{-- Pilih bintang --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Rating</label>
                            <div class="flex gap-1" id="star-selector">
                                @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="bintang" value="{{ $i }}" class="sr-only" required>
                                    <svg class="w-8 h-8 text-gray-200 hover:text-amber-400 transition-colors star-icon"
                                        data-value="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-400 mt-1" id="star-label">Pilih bintang</p>
                        </div>

                        {{-- Komentar --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Komentar (opsional)</label>
                            <textarea name="komentar" rows="3"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm resize-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300"
                                placeholder="Ceritakan pengalamanmu dengan produk ini..."></textarea>
                        </div>

                        <button type="submit"
                            class="px-6 py-2.5 bg-amber-400 hover:bg-amber-500 text-white font-bold rounded-xl transition-colors text-sm shadow-sm">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
                @else
                <div class="bg-green-50 border border-green-100 rounded-xl px-4 py-3 mb-6 text-sm text-green-700 flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kamu sudah memberikan ulasan untuk semua pesanan produk ini.
                </div>
                @endif
            @endif
        @endif
    @endauth

    {{-- ===== LIST REVIEW ===== --}}
    <div class="space-y-4">
        <h3 class="font-bold text-gray-800">
            Semua Ulasan
            @if ($reviews->count() > 0)
                <span class="text-sm font-normal text-gray-400 ml-1">({{ $reviews->count() }})</span>
            @endif
        </h3>

        @forelse ($reviews as $review)
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    {{-- Avatar inisial --}}
                    <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-800">{{ $review->user->name ?? 'Pengguna' }}</div>
                        <div class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                {{-- Bintang --}}
                <div class="flex gap-0.5 flex-shrink-0">
                    @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $review->bintang ? 'text-amber-400' : 'text-gray-200' }}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
            </div>

            @if ($review->komentar)
            <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ $review->komentar }}</p>
            @endif

            {{-- Hapus review milik sendiri --}}
            @auth
            @if (auth()->id() === $review->user_id)
            <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST"
                class="mt-3" onsubmit="return confirm('Hapus ulasan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-medium">Hapus ulasan</button>
            </form>
            @endif
            @endauth
        </div>

        @empty
        <div class="text-center py-12 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <p class="text-sm">Belum ada ulasan. Jadilah yang pertama!</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    // Interaksi klik bintang di form review
    const starLabels = ['', 'Buruk', 'Kurang', 'Cukup', 'Bagus', 'Sangat Bagus'];
    document.querySelectorAll('#star-selector label').forEach((label, idx) => {
        label.addEventListener('click', () => {
            const val = idx + 1;
            document.querySelectorAll('.star-icon').forEach((icon, i) => {
                icon.classList.toggle('text-amber-400', i < val);
                icon.classList.toggle('text-gray-200', i >= val);
            });
            document.getElementById('star-label').textContent = starLabels[val];
        });
    });
</script>