<template>
    <PublicLayout :forceSolid="true">
        <Head>
            <title>{{ objekWisata.nama_objek }} - Wisata Manggarai Barat</title>
            <meta
                name="description"
                :content="
                    objekWisata.keterangan
                        ? objekWisata.keterangan.substring(0, 160)
                        : 'Jelajahi keindahan ' + objekWisata.nama_objek
                "
            />
            <meta
                property="og:title"
                :content="
                    objekWisata.nama_objek + ' - SIG Wisata Manggarai Barat'
                "
            />
            <meta
                property="og:description"
                :content="
                    objekWisata.keterangan
                        ? objekWisata.keterangan.substring(0, 160)
                        : 'Informasi detail tentang ' + objekWisata.nama_objek
                "
            />
            <meta property="og:image" :content="mainPhoto" />
            <component :is="'script'" type="application/ld+json">
                {{ jsonLd }}
            </component>
        </Head>
        <!-- Sticky Header Background -->
        <div class="h-20 bg-gray-900"></div>

        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Breadcrumbs & Title -->
                <div class="mb-10 space-y-4">
                    <div
                        class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400"
                    >
                        <a :href="route('home')" class="hover:text-primary"
                            >Home</a
                        >
                        <svg
                            class="w-3 h-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path d="M9 5l7 7-7 7" stroke-width="2" />
                        </svg>
                        <a
                            :href="route('public.objek-wisata.index')"
                            class="hover:text-primary"
                            >Destinasi</a
                        >
                        <svg
                            class="w-3 h-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path d="M9 5l7 7-7 7" stroke-width="2" />
                        </svg>
                        <span class="text-gray-900">{{
                            objekWisata.nama_objek
                        }}</span>
                    </div>
                    <div
                        class="flex flex-col md:flex-row md:items-end justify-between gap-6"
                    >
                        <div class="space-y-2">
                            <div
                                class="text-xs font-black text-primary uppercase tracking-[0.2em] italic"
                            >
                                {{ objekWisata.kecamatan.nama_kecamatan }},
                                Manggarai Barat
                            </div>
                            <h1
                                class="text-4xl md:text-6xl font-black text-gray-900 tracking-tighter"
                            >
                                {{ objekWisata.nama_objek }}
                            </h1>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden md:block">
                                <div
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1"
                                >
                                    Rating Pengunjung
                                </div>
                                <div
                                    class="flex items-center gap-2 justify-end"
                                >
                                    <div class="flex text-yellow-500">
                                        <svg
                                            v-for="i in 5"
                                            :key="i"
                                            class="w-4 h-4"
                                            :class="
                                                i <=
                                                Math.round(
                                                    objekWisata.rating_avg || 0,
                                                )
                                                    ? 'fill-current'
                                                    : 'text-gray-200'
                                            "
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-lg font-black text-gray-900"
                                        >{{
                                            Number(
                                                objekWisata.rating_avg || 0,
                                            ).toFixed(1)
                                        }}</span
                                    >
                                </div>
                            </div>
                            <button
                                @click="toggleFavorite"
                                :class="[
                                    'p-4 rounded-2xl transition-all shadow-sm',
                                    isFavorited
                                        ? 'bg-pink-50 text-pink-500'
                                        : 'bg-gray-50 hover:bg-pink-50 hover:text-pink-500 text-gray-400',
                                ]"
                            >
                                <svg
                                    class="w-6 h-6"
                                    :fill="
                                        isFavorited ? 'currentColor' : 'none'
                                    "
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                        stroke-width="2"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <!-- Left Side: Content -->
                    <div class="lg:col-span-8 space-y-12">
                        <!-- Image Gallery -->
                        <div class="relative group">
                            <div
                                class="aspect-[16/9] rounded-[3rem] overflow-hidden bg-gray-100 shadow-2xl relative"
                            >
                                <img
                                    :src="mainPhoto"
                                    class="w-full h-full object-cover"
                                />
                                <!-- Photo Indicators/thumbnails overlaid? Let's just do a simple list for now -->
                            </div>
                            <!-- Thumbnails -->
                            <div
                                v-if="objekWisata.fotos.length > 1"
                                class="flex gap-4 mt-6 overflow-x-auto pb-4 no-scrollbar"
                            >
                                <button
                                    v-for="foto in objekWisata.fotos"
                                    :key="foto.id"
                                    @click="activePhoto = foto.path"
                                    class="relative flex-shrink-0 w-32 aspect-video rounded-2xl overflow-hidden border-2 transition-all"
                                    :class="
                                        activePhoto === foto.path
                                            ? 'border-primary shadow-lg scale-105'
                                            : 'border-transparent opacity-60 hover:opacity-100'
                                    "
                                >
                                    <img
                                        :src="'/storage/' + foto.path"
                                        class="w-full h-full object-cover"
                                    />
                                </button>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-1 bg-primary rounded-full"
                                ></div>
                                <h3
                                    class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]"
                                >
                                    Tentang Destinasi
                                </h3>
                            </div>
                            <div
                                class="prose prose-lg max-w-none text-gray-600 font-medium leading-relaxed"
                            >
                                {{ objekWisata.keterangan }}
                            </div>
                        </div>

                        <!-- Facilities -->
                        <div
                            v-if="objekWisata.fasilitas.length > 0"
                            class="space-y-8"
                        >
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-1 bg-primary rounded-full"
                                ></div>
                                <h3
                                    class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]"
                                >
                                    Fasilitas Tersedia
                                </h3>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                                <button
                                    v-for="fas in objekWisata.fasilitas"
                                    :key="fas.id"
                                    @click="openFasilitasModal(fas)"
                                    class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 items-center text-center space-y-3 group hover:bg-primary transition-all duration-300 relative overflow-hidden"
                                >
                                    <component
                                        :is="
                                            getFasilitasIcon(
                                                fas.kategori_fasilitas,
                                            )
                                        "
                                        class="w-8 h-8 text-primary group-hover:text-white mx-auto transition-transform group-hover:scale-110"
                                        stroke-width="2"
                                    />
                                    <div
                                        class="text-xs font-black text-gray-900 group-hover:text-white uppercase tracking-wider"
                                    >
                                        {{ fas.nama_fasilitas }}
                                    </div>

                                    <div
                                        class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4"
                                    >
                                        <span
                                            class="bg-white text-primary text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl shadow-lg ring-2 ring-white/50 transform translate-y-4 group-hover:translate-y-0 transition-transform"
                                            >Lihat Detail</span
                                        >
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Reviews (Static for now) -->
                        <div class="space-y-8">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-1 bg-primary rounded-full"
                                ></div>
                                <h3
                                    class="text-sm font-black text-gray-900 uppercase tracking-[0.2em]"
                                >
                                    Review Pengunjung
                                </h3>
                            </div>
                            <div
                                v-if="objekWisata.reviews.length > 0"
                                class="space-y-6"
                            >
                                <div
                                    v-for="review in objekWisata.reviews"
                                    :key="review.id"
                                    class="p-8 bg-white border border-gray-100 rounded-[2.5rem] shadow-sm space-y-4"
                                >
                                    <div
                                        class="flex justify-between items-start"
                                    >
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black"
                                            >
                                                {{ review.user.name.charAt(0) }}
                                            </div>
                                            <div>
                                                <div
                                                    class="font-black text-gray-900"
                                                >
                                                    {{ review.user.name }}
                                                </div>
                                                <div
                                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"
                                                >
                                                    {{
                                                        formatDate(
                                                            review.created_at,
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-500">
                                            <svg
                                                v-for="i in 5"
                                                :key="i"
                                                class="w-3.5 h-3.5"
                                                :class="
                                                    i <= review.rating
                                                        ? 'fill-current'
                                                        : 'text-gray-200'
                                                "
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Judul -->
                                    <p
                                        v-if="review.judul"
                                        class="text-sm font-black text-gray-900"
                                    >
                                        {{ review.judul }}
                                    </p>

                                    <!-- Komentar -->
                                    <p
                                        class="text-gray-600 font-medium leading-relaxed italic"
                                    >
                                        "{{ review.komentar }}"
                                    </p>

                                    <!-- Foto Review -->
                                    <div
                                        v-if="
                                            review.fotos &&
                                            review.fotos.length > 0
                                        "
                                        class="grid grid-cols-3 sm:grid-cols-5 gap-2 pt-3"
                                    >
                                        <a
                                            v-for="foto in review.fotos"
                                            :key="foto.id"
                                            :href="'/storage/' + foto.path"
                                            target="_blank"
                                            class="group relative aspect-square rounded-2xl overflow-hidden border border-gray-100 hover:border-primary transition-all shadow-sm"
                                        >
                                            <img
                                                :src="'/storage/' + foto.path"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                            />
                                            <div
                                                class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center"
                                            >
                                                <svg
                                                    class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                    />
                                                </svg>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-else
                                class="p-12 text-center bg-gray-50 rounded-[2.5rem] border border-dashed border-gray-200"
                            >
                                <p
                                    class="text-gray-400 font-bold uppercase tracking-widest text-sm"
                                >
                                    Belum ada review untuk tempat ini.
                                </p>
                            </div>

                            <!-- Review Form -->
                            <div
                                class="mt-12 p-8 md:p-12 bg-gray-50 rounded-[3rem] border border-gray-100 space-y-8"
                            >
                                <div class="space-y-2">
                                    <h4
                                        class="text-2xl font-black text-gray-900 tracking-tight"
                                    >
                                        Berikan
                                        <span class="text-primary italic"
                                            >Ulasan Anda</span
                                        >
                                    </h4>
                                    <p
                                        class="text-gray-500 text-sm font-medium"
                                    >
                                        Bagikan pengalaman Anda mengunjungi
                                        tempat ini kepada wisatawan lain.
                                    </p>
                                </div>

                                <!-- Toast Notif -->
                                <transition
                                    enter-active-class="transition duration-300 ease-out"
                                    enter-from-class="opacity-0 -translate-y-2"
                                    enter-to-class="opacity-100 translate-y-0"
                                    leave-active-class="transition duration-200"
                                    leave-from-class="opacity-100"
                                    leave-to-class="opacity-0"
                                >
                                    <div
                                        v-if="reviewToast"
                                        class="flex items-start gap-3 p-4 rounded-2xl text-sm font-bold"
                                        :class="
                                            reviewToast.type === 'success'
                                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                                : 'bg-red-50 text-red-700 border border-red-200'
                                        "
                                    >
                                        <span class="text-xl flex-shrink-0">{{
                                            reviewToast.type === "success"
                                                ? "✅"
                                                : "❌"
                                        }}</span>
                                        <p>{{ reviewToast.message }}</p>
                                    </div>
                                </transition>

                                <div
                                    v-if="!$page.props.auth.user"
                                    class="p-6 bg-white rounded-2xl border border-gray-100 text-center space-y-4"
                                >
                                    <p class="text-gray-600 font-bold">
                                        Silakan login terlebih dahulu untuk
                                        memberikan ulasan.
                                    </p>
                                    <a
                                        :href="route('login')"
                                        class="inline-flex bg-primary text-white px-8 py-3 rounded-xl font-black uppercase tracking-widest text-[10px] hover:scale-105 transition-all shadow-lg shadow-primary/20"
                                        >Login Sekarang</a
                                    >
                                </div>

                                <form
                                    v-else
                                    @submit.prevent="submitReview"
                                    class="space-y-6"
                                >
                                    <!-- Rating -->
                                    <div class="space-y-4">
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2"
                                            >Rating Anda</label
                                        >
                                        <div class="flex gap-2">
                                            <button
                                                v-for="i in 5"
                                                :key="i"
                                                type="button"
                                                @click="form.rating = i"
                                                class="w-12 h-12 rounded-xl flex items-center justify-center transition-all border-2"
                                                :class="
                                                    form.rating >= i
                                                        ? 'bg-yellow-50 border-yellow-400 text-yellow-500 shadow-lg shadow-yellow-500/10'
                                                        : 'bg-white border-gray-100 text-gray-200 hover:border-yellow-200'
                                                "
                                            >
                                                <svg
                                                    class="w-6 h-6 fill-current"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                        <span
                                            v-if="form.errors.rating"
                                            class="text-xs text-red-500 font-bold ml-2"
                                            >{{ form.errors.rating }}</span
                                        >
                                    </div>

                                    <!-- Judul -->
                                    <div class="space-y-2">
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2"
                                            >Judul (Opsional)</label
                                        >
                                        <input
                                            v-model="form.judul"
                                            type="text"
                                            placeholder="Contoh: Sangat Indah!"
                                            class="w-full bg-white border border-gray-200 rounded-2xl px-6 py-4 text-gray-900 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all font-bold placeholder-gray-300"
                                        />
                                    </div>

                                    <!-- Komentar -->
                                    <div class="space-y-2">
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2"
                                            >Komentar Ulasan</label
                                        >
                                        <textarea
                                            v-model="form.komentar"
                                            rows="4"
                                            placeholder="Tuliskan detail pengalaman Anda di sini..."
                                            class="w-full bg-white border border-gray-200 rounded-3xl px-6 py-4 text-gray-900 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all font-bold placeholder-gray-300"
                                        ></textarea>
                                        <span
                                            v-if="form.errors.komentar"
                                            class="text-xs text-red-500 font-bold ml-2"
                                            >{{ form.errors.komentar }}</span
                                        >
                                    </div>

                                    <!-- Upload Foto -->
                                    <div class="space-y-4">
                                        <label
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2"
                                            >Foto (Maks. 5)</label
                                        >
                                        <label
                                            class="flex flex-col items-center justify-center gap-3 px-6 py-8 border-2 border-dashed border-gray-200 rounded-3xl bg-white hover:border-primary hover:bg-primary/5 transition-all cursor-pointer group"
                                        >
                                            <svg
                                                class="w-10 h-10 text-gray-300 group-hover:text-primary transition-colors"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                            <div class="text-center">
                                                <p
                                                    class="text-sm font-black text-primary"
                                                >
                                                    Unggah foto
                                                </p>
                                                <p
                                                    class="text-xs text-gray-400 font-medium mt-1"
                                                >
                                                    PNG, JPG hingga 10MB · Maks.
                                                    5 foto
                                                </p>
                                            </div>
                                            <input
                                                type="file"
                                                class="sr-only"
                                                @change="handleFileUpload"
                                                multiple
                                                accept="image/*"
                                            />
                                        </label>

                                        <!-- Previews -->
                                        <div
                                            v-if="reviewPreviews.length"
                                            class="grid grid-cols-3 sm:grid-cols-5 gap-3"
                                        >
                                            <div
                                                v-for="(
                                                    src, index
                                                ) in reviewPreviews"
                                                :key="index"
                                                class="relative group aspect-square rounded-2xl overflow-hidden border border-gray-100 shadow-sm"
                                            >
                                                <img
                                                    :src="src"
                                                    class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                                />
                                                <button
                                                    @click.prevent="
                                                        removeReviewPhoto(index)
                                                    "
                                                    class="absolute top-1.5 right-1.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md"
                                                >
                                                    <svg
                                                        class="w-3 h-3"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            d="M6 18L18 6M6 6l12 12"
                                                            stroke-width="3"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="bg-gray-900 text-white px-10 py-5 rounded-[2rem] font-black uppercase tracking-widest text-[10px] hover:scale-105 transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{
                                            form.processing
                                                ? "Mengirim..."
                                                : "Kirim Ulasan"
                                        }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Sidebar Info -->
                    <div class="lg:col-span-4 space-y-8">
                        <!-- Quick Info Card -->
                        <div
                            class="bg-gray-900 text-white rounded-[3rem] p-8 shadow-2xl relative overflow-hidden"
                        >
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-primary/20 blur-[60px] -mr-16 -mt-16"
                            ></div>

                            <h4
                                class="text-sm font-black uppercase tracking-[0.3em] text-primary/80 mb-8"
                            >
                                Informasi Wisata
                            </h4>

                            <ul class="space-y-8 relative z-10">
                                <li class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-primary"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                                stroke-width="2"
                                            />
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div
                                            class="text-[10px] font-black text-gray-500 uppercase tracking-widest"
                                        >
                                            Lokasi
                                        </div>
                                        <div
                                            class="text-sm font-bold leading-relaxed"
                                        >
                                            {{
                                                objekWisata.alamat ||
                                                "Manggarai Barat, NTT"
                                            }}
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-primary"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                stroke-width="2"
                                            />
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div
                                            class="text-[10px] font-black text-gray-500 uppercase tracking-widest"
                                        >
                                            Jam Operasional
                                        </div>
                                        <div
                                            class="text-sm font-bold leading-relaxed"
                                        >
                                            {{
                                                objekWisata.jam_operasional ||
                                                "08:00 - 18:00 WITA"
                                            }}
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-primary"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
                                                stroke-width="2"
                                            />
                                        </svg>
                                    </div>
                                    <div class="space-y-2 flex-1">
                                        <div
                                            class="text-[10px] font-black text-gray-500 uppercase tracking-widest"
                                        >
                                            Harga Tiket
                                        </div>
                                        <div class="space-y-1.5 pt-1">
                                            <div
                                                class="flex justify-between items-center bg-white/5 p-2 rounded-xl"
                                            >
                                                <span
                                                    class="text-[10px] font-bold text-gray-400"
                                                    >Lokal</span
                                                >
                                                <span
                                                    class="text-xs font-black text-emerald-400"
                                                    >{{
                                                        objekWisata.harga_tiket_lokal
                                                            ? "Rp " +
                                                              Number(
                                                                  objekWisata.harga_tiket_lokal,
                                                              ).toLocaleString(
                                                                  "id-ID",
                                                              )
                                                            : "Gratis"
                                                    }}</span
                                                >
                                            </div>
                                            <div
                                                class="flex justify-between items-center bg-white/5 p-2 rounded-xl"
                                            >
                                                <span
                                                    class="text-[10px] font-bold text-gray-400"
                                                    >Domestik</span
                                                >
                                                <span
                                                    class="text-xs font-black text-emerald-400"
                                                    >{{
                                                        objekWisata.harga_tiket_domestik
                                                            ? "Rp " +
                                                              Number(
                                                                  objekWisata.harga_tiket_domestik,
                                                              ).toLocaleString(
                                                                  "id-ID",
                                                              )
                                                            : "Gratis"
                                                    }}</span
                                                >
                                            </div>
                                            <div
                                                class="flex justify-between items-center bg-white/5 p-2 rounded-xl"
                                            >
                                                <span
                                                    class="text-[10px] font-bold text-gray-400"
                                                    >Asing</span
                                                >
                                                <span
                                                    class="text-xs font-black text-emerald-400"
                                                    >{{
                                                        objekWisata.harga_tiket_asing
                                                            ? "Rp " +
                                                              Number(
                                                                  objekWisata.harga_tiket_asing,
                                                              ).toLocaleString(
                                                                  "id-ID",
                                                              )
                                                            : "Gratis"
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-primary"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7.5V5z"
                                                stroke-width="2"
                                            />
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div
                                            class="text-[10px] font-black text-gray-500 uppercase tracking-widest"
                                        >
                                            Hubungi
                                        </div>
                                        <div
                                            class="text-sm font-bold leading-relaxed"
                                        >
                                            {{ objekWisata.no_telepon || "-" }}
                                        </div>
                                    </div>
                                </li>
                                <li
                                    v-if="
                                        objekWisata.akses_transportasi &&
                                        objekWisata.akses_transportasi.length >
                                            0
                                    "
                                    class="flex items-start gap-4"
                                >
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0"
                                    >
                                        <!-- Ikon Car / Transportasi -->
                                        <svg
                                            class="w-5 h-5 text-primary"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                                            />
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <div
                                            class="text-[10px] font-black text-gray-500 uppercase tracking-widest"
                                        >
                                            Akses Transportasi
                                        </div>
                                        <div
                                            class="text-sm font-bold leading-relaxed"
                                        >
                                            {{
                                                objekWisata.akses_transportasi.join(
                                                    ", ",
                                                )
                                            }}
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <a
                                :href="`https://www.google.com/maps?q=${objekWisata.latitude},${objekWisata.longitude}`"
                                target="_blank"
                                class="w-full mt-10 bg-primary text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs shadow-md hover:scale-[1.02] transition-all flex items-center justify-center gap-2"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        stroke-width="2"
                                    />
                                    <path
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        stroke-width="2"
                                    />
                                </svg>
                                Kunjungi Sekarang
                            </a>

                            <div class="mt-8 border-t border-white/10 pt-8">
                                <h4
                                    class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500 mb-4 text-center"
                                >
                                    Bagikan
                                </h4>
                                <div class="flex gap-3 justify-center">
                                    <a
                                        :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent($page.props.ziggy.location)}`"
                                        target="_blank"
                                        class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                                            />
                                        </svg>
                                    </a>
                                    <a
                                        :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent($page.props.ziggy.location)}&text=${encodeURIComponent(objekWisata.nama_objek)}`"
                                        target="_blank"
                                        class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
                                            />
                                        </svg>
                                    </a>
                                    <a
                                        :href="`https://wa.me/?text=${encodeURIComponent(objekWisata.nama_objek + ' ' + $page.props.ziggy.location)}`"
                                        target="_blank"
                                        class="w-10 h-10 rounded-xl bg-green-500 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"
                                            />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Mini Map Card -->
                        <div
                            class="bg-white rounded-[3rem] border border-gray-100 p-8 shadow-sm space-y-6"
                        >
                            <h4
                                class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400"
                            >
                                Peta Lokasi
                            </h4>
                            <div
                                id="mini-map"
                                class="w-full h-64 rounded-3xl overflow-hidden border border-gray-100 z-10"
                            ></div>
                            <div class="text-center">
                                <a
                                    :href="`https://www.google.com/maps/dir/?api=1&destination=${objekWisata.latitude},${objekWisata.longitude}`"
                                    target="_blank"
                                    class="text-xs font-black text-primary uppercase tracking-widest hover:underline"
                                >
                                    Lihat di Google Maps
                                </a>
                            </div>
                        </div>

                        <!-- Nearby UMKM Card -->
                        <div
                            v-if="nearbyUmkms && nearbyUmkms.length > 0"
                            class="bg-white rounded-[3rem] border border-gray-100 p-8 shadow-sm space-y-6"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-1 bg-amber-500 rounded-full"
                                ></div>
                                <h4
                                    class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400"
                                >
                                    UMKM Terdekat
                                </h4>
                            </div>
                            <div class="space-y-4">
                                <div
                                    v-for="umkm in nearbyUmkms"
                                    :key="umkm.id"
                                    class="flex items-center gap-4 p-4 rounded-2xl bg-amber-50/60 hover:bg-amber-50 border border-amber-100/60 hover:border-amber-200 transition-all group"
                                >
                                    <!-- Photo / Emoji -->
                                    <div
                                        class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-amber-100 flex items-center justify-center shadow-inner"
                                    >
                                        <img
                                            v-if="
                                                umkm.fotos &&
                                                umkm.fotos.length > 0
                                            "
                                            :src="
                                                '/storage/' + umkm.fotos[0].path
                                            "
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        />
                                        <span v-else class="text-2xl">{{
                                            getUmkmEmoji(umkm.kategori)
                                        }}</span>
                                    </div>
                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <div
                                            class="flex items-center gap-2 mb-1"
                                        >
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-200 text-amber-800 text-[9px] font-black uppercase tracking-wide leading-none"
                                                >{{ umkm.kategori }}</span
                                            >
                                        </div>
                                        <p
                                            class="font-black text-gray-900 text-sm truncate leading-tight group-hover:text-amber-700 transition-colors"
                                        >
                                            {{ umkm.nama_umkm }}
                                        </p>
                                        <div
                                            class="flex items-center gap-1 mt-1"
                                        >
                                            <svg
                                                class="w-3 h-3 text-amber-500"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                                    stroke-width="2"
                                                />
                                                <path
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                                    stroke-width="2"
                                                />
                                            </svg>
                                            <span
                                                class="text-[10px] font-bold text-amber-600"
                                                >{{
                                                    Number(
                                                        umkm.distance,
                                                    ).toFixed(1)
                                                }}
                                                km dari sini</span
                                            >
                                        </div>
                                    </div>
                                    <!-- Direction Button -->
                                    <button
                                        @click="openDirection(umkm)"
                                        title="Petunjuk Arah"
                                        class="w-10 h-10 flex-shrink-0 rounded-2xl bg-amber-500 hover:bg-amber-600 active:scale-95 text-white flex items-center justify-center shadow-lg shadow-amber-500/30 transition-all"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal Detail Fasilitas -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isFasilitasModalOpen"
                class="fixed inset-0 z-[6000] flex items-center justify-center p-4 sm:p-6"
            >
                <!-- Backdrop -->
                <div
                    class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
                    @click="closeFasilitasModal"
                ></div>

                <!-- Modal Panel -->
                <transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4"
                >
                    <div
                        v-if="isFasilitasModalOpen"
                        class="relative w-full max-w-2xl bg-white rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
                    >
                        <!-- Tombol Close -->
                        <button
                            @click="closeFasilitasModal"
                            class="absolute top-6 right-6 z-10 w-10 h-10 bg-black/40 hover:bg-black/60 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-colors"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                        </button>

                        <!-- Header / Gambar (Opsional jika fasilitas ada gambar) -->
                        <div
                            class="relative h-48 md:h-64 bg-slate-100 flex-shrink-0"
                        >
                            <!-- Background gradient pattern if no photo -->
                            <div
                                v-if="!selectedFasilitas?.fotos?.length"
                                class="absolute inset-0 bg-gradient-to-br from-primary/20 to-blue-600/20"
                            ></div>
                            <div
                                v-if="!selectedFasilitas?.fotos?.length"
                                class="absolute inset-0 flex items-center justify-center text-6xl opacity-20 transform scale-150"
                            >
                                <component
                                    :is="
                                        getFasilitasIcon(
                                            selectedFasilitas?.kategori_fasilitas,
                                        )
                                    "
                                    class="w-20 h-20"
                                    stroke-width="1.5"
                                />
                            </div>

                            <!-- Display photo if exists -->
                            <img
                                v-if="selectedFasilitas?.fotos?.length"
                                :src="
                                    '/storage/' +
                                    selectedFasilitas.fotos[0].path
                                "
                                class="w-full h-full object-cover"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"
                                v-if="selectedFasilitas?.fotos?.length"
                            ></div>

                            <div
                                class="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-white to-transparent"
                                v-if="!selectedFasilitas?.fotos?.length"
                            ></div>
                        </div>

                        <!-- Konten Detail -->
                        <div
                            class="p-8 md:p-12 overflow-y-auto no-scrollbar pb-12"
                        >
                            <div class="space-y-6">
                                <div>
                                    <div
                                        class="inline-block px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-lg mb-3"
                                    >
                                        {{
                                            selectedFasilitas?.kategori_fasilitas ||
                                            "Fasilitas Umum"
                                        }}
                                    </div>
                                    <h3
                                        class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight"
                                    >
                                        {{ selectedFasilitas?.nama_fasilitas }}
                                    </h3>
                                </div>

                                <div
                                    class="prose prose-sm md:prose-base text-slate-600 leading-relaxed font-medium"
                                >
                                    <p v-if="selectedFasilitas?.deskripsi">
                                        {{ selectedFasilitas.deskripsi }}
                                    </p>
                                    <p v-else class="italic text-slate-400">
                                        Tidak ada deskripsi rinci untuk
                                        fasilitas ini.
                                    </p>
                                </div>

                                <div
                                    v-if="selectedFasilitas?.fotos?.length > 1"
                                    class="pt-6 border-t border-slate-100"
                                >
                                    <h4
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4"
                                    >
                                        Galeri Fasilitas
                                    </h4>
                                    <div
                                        class="grid grid-cols-2 sm:grid-cols-3 gap-3"
                                    >
                                        <a
                                            v-for="foto in selectedFasilitas.fotos.slice(
                                                1,
                                            )"
                                            :key="foto.id"
                                            :href="'/storage/' + foto.path"
                                            target="_blank"
                                            class="aspect-square sm:aspect-video rounded-xl overflow-hidden border border-slate-100 shadow-sm relative group block"
                                        >
                                            <img
                                                :src="'/storage/' + foto.path"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                            />
                                            <div
                                                class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center"
                                            >
                                                <svg
                                                    class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                    />
                                                </svg>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div
                                    class="pt-6 border-t border-slate-100 flex justify-end"
                                >
                                    <button
                                        @click="closeFasilitasModal"
                                        class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-primary transition-colors"
                                    >
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </PublicLayout>
</template>

<script setup>
import { onMounted, ref, computed, watch } from "vue";
import { useForm, Head, usePage, router } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";
import {
    Wifi,
    Car,
    Coffee,
    Info,
    Utensils,
    ShoppingBag,
    Map as MapIcon,
    Bath,
    ShieldCheck,
    Bed,
    Camera,
    TreePine,
    MapPin,
} from "lucide-vue-next";

const props = defineProps({
    objekWisata: Object,
    isFavorited: Boolean,
    nearbyUmkms: Array,
});

// Fasilitas Modal
const isFasilitasModalOpen = ref(false);
const selectedFasilitas = ref(null);

const openFasilitasModal = (fasilitas) => {
    selectedFasilitas.value = fasilitas;
    isFasilitasModalOpen.value = true;
};

const closeFasilitasModal = () => {
    isFasilitasModalOpen.value = false;
    setTimeout(() => {
        selectedFasilitas.value = null;
    }, 300);
};

const getFasilitasIcon = (kategoriFasilitas) => {
    const defaultIcon = MapPin;
    if (!kategoriFasilitas) return defaultIcon;

    const kat = kategoriFasilitas.toLowerCase();

    if (kat.includes("parkir")) return Car;
    if (
        kat.includes("makan") ||
        kat.includes("restoran") ||
        kat.includes("kuliner")
    )
        return Utensils;
    if (kat.includes("minum") || kat.includes("cafe") || kat.includes("kopi"))
        return Coffee;
    if (kat.includes("informasi")) return Info;
    if (
        kat.includes("belanja") ||
        kat.includes("oleh-oleh") ||
        kat.includes("toko")
    )
        return ShoppingBag;
    if (
        kat.includes("toilet") ||
        kat.includes("kamar mandi") ||
        kat.includes("wc")
    )
        return Bath;
    if (kat.includes("keamanan") || kat.includes("pos")) return ShieldCheck;
    if (
        kat.includes("penginapan") ||
        kat.includes("hotel") ||
        kat.includes("villa")
    )
        return Bed;
    if (kat.includes("foto") || kat.includes("spot")) return Camera;
    if (
        kat.includes("alam") ||
        kat.includes("taman") ||
        kat.includes("camping")
    )
        return TreePine;
    if (kat.includes("wifi") || kat.includes("internet")) return Wifi;

    return defaultIcon;
};

const toggleFavorite = () => {
    if (!usePage().props.auth.user) {
        router.visit(route("login"));
        return;
    }

    router.post(
        route("favorites.toggle"),
        {
            objek_wisata_id: props.objekWisata.id,
        },
        {
            preserveScroll: true,
        },
    );
};

const activePhoto = ref(
    props.objekWisata.fotos.length > 0 ? props.objekWisata.fotos[0].path : null,
);

const mainPhoto = computed(() => {
    return activePhoto.value
        ? "/storage/" + activePhoto.value
        : "https://images.unsplash.com/photo-1544911845-1f34a3eb46b1?q=80&w=1000";
});

const jsonLd = computed(() => {
    return JSON.stringify({
        "@context": "https://schema.org",
        "@type": "TouristAttraction",
        name: props.objekWisata.nama_objek,
        description: props.objekWisata.keterangan,
        image: [mainPhoto.value],
        address: {
            "@type": "PostalAddress",
            addressLocality: props.objekWisata.kecamatan.nama_kecamatan,
            addressRegion: "Nusa Tenggara Timur",
            addressCountry: "ID",
        },
        geo: {
            "@type": "GeoCoordinates",
            latitude: props.objekWisata.latitude,
            longitude: props.objekWisata.longitude,
        },
        url: window.location.href,
        telephone: props.objekWisata.no_telepon,
    });
});

const form = useForm({
    id_objek: props.objekWisata.id,
    rating: 5,
    judul: "",
    komentar: "",
    fotos: [],
});

const reviewPreviews = ref([]);

const handleFileUpload = (e) => {
    const files = Array.from(e.target.files);
    form.fotos = [...form.fotos, ...files];
    files.forEach((file) => {
        const reader = new FileReader();
        reader.onload = (ev) => reviewPreviews.value.push(ev.target.result);
        reader.readAsDataURL(file);
    });
};

const removeReviewPhoto = (index) => {
    form.fotos.splice(index, 1);
    reviewPreviews.value.splice(index, 1);
};

const reviewToast = ref(null);

const showReviewToast = (message, type = "success") => {
    reviewToast.value = { message, type };
    setTimeout(() => {
        reviewToast.value = null;
    }, 5000);
};

const submitReview = () => {
    console.log("Submitting review...", {
        id_objek: form.id_objek,
        rating: form.rating,
        komentar: form.komentar,
    });

    form.post(route("public.reviews.store"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset("rating", "judul", "komentar", "fotos");
            reviewPreviews.value = [];
            showReviewToast(
                "Terima kasih! Ulasan Anda telah dikirim dan menunggu moderasi admin.",
                "success",
            );
        },
        onError: (errors) => {
            console.log("Form errors:", errors);
            const firstError = Object.values(errors)[0];
            showReviewToast(
                firstError || "Terjadi kesalahan, silakan coba lagi.",
                "error",
            );
        },
        onFinish: () => {
            console.log(
                "Request finished. Processing:",
                form.processing,
                "Errors:",
                form.errors,
            );
        },
    });
};

// Tangkap flash message dari backend (misal: review sudah ada)
const page = usePage();
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.error) showReviewToast(flash.error, "error");
        if (flash?.message) showReviewToast(flash.message, "success");
    },
    { immediate: false, deep: true },
);

onMounted(() => {
    // Mini Map
    const map = L.map("mini-map", {
        zoomControl: false,
        attributionControl: false,
    }).setView([props.objekWisata.latitude, props.objekWisata.longitude], 14);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(
        map,
    );

    const icon = L.icon({
        iconUrl:
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
        shadowUrl:
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
    });

    L.marker([props.objekWisata.latitude, props.objekWisata.longitude], {
        icon,
    }).addTo(map);
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const openDirection = (umkm) => {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${umkm.latitude},${umkm.longitude}`;
    window.open(url, "_blank");
};

const umkmIconMap = {
    "Warung makan": "🍽️",
    Kios: "🛒",
    Villa: "🏡",
    Hotel: "🏨",
    Penginapan: "🛏️",
};

const getUmkmEmoji = (kategori) => umkmIconMap[kategori] || "🏪";
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
