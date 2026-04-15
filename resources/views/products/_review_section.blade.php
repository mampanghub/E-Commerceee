{{-- resources/views/products/_review_section.blade.php --}}

<div class="mt-10 border-t border-blue-100 pt-10">

    {{-- ===== RATING SUMMARY ===== --}}
    <div class="flex items-center gap-8 mb-10">
        <div class="text-center shrink-0">
            <div class="text-6xl font-black text-slate-900 leading-none">
                {{ $reviews->count() > 0 ? number_format($reviews->avg('bintang'), 1) : '0.0' }}
            </div>
            <div class="flex justify-center gap-0.5 my-2">
                @php $avg = $reviews->avg('bintang') ?? 0; @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= round($avg) ? 'text-blue-500' : 'text-slate-200' }}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
            </div>
            <div class="text-xs font-bold text-slate-400">{{ $reviews->count() }} ulasan</div>
        </div>

        <div class="flex-1 space-y-2">
            @for ($star = 5; $star >= 1; $star--)
                @php
                    $count = $reviews->where('bintang', $star)->count();
                    $pct = $reviews->count() > 0 ? ($count / $reviews->count()) * 100 : 0;
                @endphp
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-500 w-3 text-right shrink-0">{{ $star }}</span>
                    <svg class="w-3 h-3 text-blue-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <div class="flex-1 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-blue-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="text-xs text-slate-400 w-4 shrink-0">{{ $count }}</span>
                </div>
            @endfor
        </div>
    </div>

    {{-- ===== FORM TULIS REVIEW ===== --}}
    @auth
        @if (auth()->user()->role === 'pembeli' && isset($eligibleOrders) && $eligibleOrders->count() > 0)
            @php
                $sudahSemua = $eligibleOrders->every(
                    fn($o) => $reviews->where('user_id', auth()->id())->where('order_id', $o->order_id)->count() > 0
                );

                $orderPreviewData = [];
                foreach ($eligibleOrders as $ord) {
                    if ($reviews->where('user_id', auth()->id())->where('order_id', $ord->order_id)->count() > 0) continue;

                    $item = $ord->items->first();
                    if (!$item) continue;

                    $gambar = null;
                    if ($item->variant_id) {
                        $gambar = $product->images->where('variant_id', $item->variant_id)->first();
                    }
                    $gambar = $gambar ?? $product->primaryImage ?? $product->images->first();

                    $orderPreviewData[$ord->order_id] = [
                        'img'    => $gambar ? asset('storage/' . $gambar->gambar) : 'https://placehold.co/56x56?text=?',
                        'varian' => $item->variant?->nama_varian ?? null,
                        'date'   => $ord->created_at->format('d M Y'),
                    ];
                }

                $firstOrderId = array_key_first($orderPreviewData);
            @endphp

            @if (!$sudahSemua && $firstOrderId)
                <div class="bg-white border border-blue-100 rounded-2xl p-6 mb-8 shadow-sm">
                    <h3 class="font-black text-slate-900 text-base mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Tulis Ulasan
                    </h3>

                    <div class="flex items-center gap-4 bg-blue-50 border border-blue-100 rounded-xl p-3 mb-5">
                        <img id="review-preview-img"
                            src="{{ $orderPreviewData[$firstOrderId]['img'] }}"
                            class="w-14 h-14 rounded-xl object-cover bg-blue-100 shrink-0 transition-all duration-200"
                            onerror="this.src='https://placehold.co/56x56?text=?'">
                        <div>
                            <p class="text-sm font-black text-slate-900">{{ $product->nama_produk }}</p>
                            <p id="review-preview-variant"
                               class="text-xs font-bold text-blue-500 mt-0.5 {{ $orderPreviewData[$firstOrderId]['varian'] ? '' : 'hidden' }}">
                                {{ $orderPreviewData[$firstOrderId]['varian'] ?? '' }}
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('reviews.store', $product->product_id) }}" method="POST" class="space-y-4">
                        @csrf

                        @if (count($orderPreviewData) > 1)
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">Dari Pesanan</label>
                                <select id="review-order-select" name="order_id"
                                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    @foreach ($orderPreviewData as $orderId => $preview)
                                        <option value="{{ $orderId }}"
                                            data-img="{{ $preview['img'] }}"
                                            data-varian="{{ $preview['varian'] ?? '' }}">
                                            Order #{{ $orderId }} — {{ $preview['date'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="order_id" value="{{ $firstOrderId }}">
                        @endif

                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Rating</label>
                            <div class="flex gap-1.5" id="star-selector">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="bintang" value="{{ $i }}" class="sr-only" required>
                                        <svg class="w-9 h-9 text-slate-200 hover:text-blue-400 transition-colors star-icon"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            <p class="text-xs text-slate-400 mt-1.5 font-medium" id="star-label">Pilih bintang</p>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-1.5">
                                Komentar <span class="normal-case font-normal">(opsional)</span>
                            </label>
                            <textarea name="komentar" rows="3"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all"
                                placeholder="Ceritakan pengalamanmu dengan produk ini..."></textarea>
                        </div>

                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-xl transition-all text-sm active:scale-95">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-6 text-sm text-blue-700 flex items-center gap-2 font-medium">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Kamu sudah memberikan ulasan untuk semua pesanan produk ini.
                </div>
            @endif
        @endif
    @endauth

    {{-- ===== LIST REVIEW — grid 3 kolom ===== --}}
    <div>
        <h3 class="font-black text-slate-900 text-base mb-4">
            Semua Ulasan
            @if ($reviews->count() > 0)
                <span class="text-sm font-normal text-slate-400 ml-1">({{ $reviews->count() }})</span>
            @endif
        </h3>

        @forelse ($reviews as $review)
            @php
                $reviewGambar = null;
                if ($review->variant_id) {
                    $reviewGambar = $product->images->where('variant_id', $review->variant_id)->first();
                }
                $reviewGambar = $reviewGambar ?? ($product->primaryImage ?? $product->images->first());
            @endphp

            @if ($loop->index % 3 === 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
            @endif

                <div class="bg-white border border-blue-50 rounded-2xl p-5 shadow-sm hover:border-blue-200 transition-all flex flex-col gap-3">
                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-black text-xs shrink-0">
                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900 leading-none">{{ $review->user->name ?? 'Pengguna' }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex gap-0.5 shrink-0">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= $review->bintang ? 'text-blue-500' : 'text-slate-200' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>

                    {{-- Foto produk + varian --}}
                    <div class="flex items-center gap-3">
                        <img src="{{ $reviewGambar ? asset('storage/' . $reviewGambar->gambar) : 'https://placehold.co/48x48?text=?' }}"
                            class="w-12 h-12 rounded-xl object-cover bg-blue-50 shrink-0"
                            onerror="this.src='https://placehold.co/48x48?text=?'">
                        @if ($review->variant_id && $review->variant)
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wide">
                                {{ $review->variant->nama_varian }}
                            </span>
                        @endif
                    </div>

                    {{-- Komentar --}}
                    @if ($review->komentar)
                        <p class="text-sm text-slate-600 leading-relaxed flex-1">{{ $review->komentar }}</p>
                    @endif

                    {{-- Hapus (owner only) --}}
                    @auth
                        @if (auth()->id() === $review->user_id)
                            <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST"
                                onsubmit="return confirm('Hapus ulasan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-bold">Hapus ulasan</button>
                            </form>
                        @endif
                    @endauth
                </div>

            @if ($loop->index % 3 === 2 || $loop->last)
                </div>
            @endif

        @empty
            <div class="text-center py-16 text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="text-sm font-medium">Belum ada ulasan. Jadilah yang pertama!</p>
            </div>
        @endforelse
    </div>
</div>

@verbatim
<script>
    const orderSelect = document.getElementById('review-order-select');
    if (orderSelect) {
        orderSelect.addEventListener('change', function () {
            const opt    = this.options[this.selectedIndex];
            const img    = opt.dataset.img;
            const varian = opt.dataset.varian;

            const previewImg    = document.getElementById('review-preview-img');
            const previewVarian = document.getElementById('review-preview-variant');

            if (previewImg) {
                previewImg.style.opacity = '0';
                setTimeout(() => {
                    previewImg.src = img || 'https://placehold.co/56x56?text=?';
                    previewImg.style.opacity = '1';
                }, 150);
            }

            if (previewVarian) {
                if (varian) {
                    previewVarian.textContent = varian;
                    previewVarian.classList.remove('hidden');
                } else {
                    previewVarian.textContent = '';
                    previewVarian.classList.add('hidden');
                }
            }
        });
    }

    const starLabels = ['', 'Buruk', 'Kurang', 'Cukup', 'Bagus', 'Sangat Bagus'];
    document.querySelectorAll('#star-selector label').forEach((label, idx) => {
        label.addEventListener('click', () => {
            const val = idx + 1;
            document.querySelectorAll('.star-icon').forEach((icon, i) => {
                icon.classList.toggle('text-blue-500', i < val);
                icon.classList.toggle('text-slate-200', i >= val);
            });
            document.getElementById('star-label').textContent = starLabels[val];
        });
    });
</script>
@endverbatim
