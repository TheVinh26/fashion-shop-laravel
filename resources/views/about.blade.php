@extends('layouts.app')

@section('content')
<main class="bg-white">
    <section class="relative h-[60vh] flex items-center justify-center overflow-hidden">
        <img src="https://images.unsplash.com/photo-1441996632127-962f9ca58bb3?auto=format&fit=crop&q=80&w=1600" 
             alt="Fashion Background" 
             class="absolute inset-0 w-full h-full object-cover grayscale-[20%]">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 text-center px-4">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-4 tracking-tighter">OUR STORY</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto font-light">
                Redefining modern elegance since 2015. We believe fashion is more than clothes—it's an expression of your soul.
            </p>
        </div>
    </section>

    <section class="py-20 container mx-auto px-6">
        <div class="flex flex-col md:flex-row items-center gap-16">
            <div class="md:w-1/2">
                <span class="text-blue-600 font-bold tracking-widest uppercase text-sm">Since 2015</span>
                <h2 class="text-4xl font-bold text-gray-900 mt-2 mb-6">Crafting Confidence Through Every Stitch</h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Founded in a small studio in the heart of the city, **Shop Fashion** began with a simple mission: to make high-end style accessible to everyone without compromising on quality or ethics.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Today, we have grown into a global community of fashion enthusiasts, designers, and artisans working together to create timeless pieces that help you feel like the best version of yourself.
                </p>
            </div>
            <div class="md:w-1/2 grid grid-cols-2 gap-4">
                <img src="https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&q=80&w=600" class="rounded-2xl shadow-lg mt-8" alt="Design process">
                <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&q=80&w=600" class="rounded-2xl shadow-lg" alt="Fashion model">
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-20">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">What Drives Us</h2>
                <div class="h-1 w-20 bg-blue-600 mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div class="p-8 bg-white rounded-3xl shadow-sm hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Quality First</h3>
                    <p class="text-gray-500 text-sm">We source only the finest sustainable fabrics to ensure your garments last for seasons, not weeks.</p>
                </div>
                <div class="p-8 bg-white rounded-3xl shadow-sm hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-pink-100 text-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Inclusivity</h3>
                    <p class="text-gray-500 text-sm">Fashion is for everyone. Our collections are designed to celebrate all body types and identities.</p>
                </div>
                <div class="p-8 bg-white rounded-3xl shadow-sm hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9-9c1.657 0 3 4.03 3 9s-1.343 9-3 9m0-18c-1.657 0-3 4.03-3 9s1.343 9 3 9m-9-9h18"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Eco-Friendly</h3>
                    <p class="text-gray-500 text-sm">From recycled packaging to carbon-neutral shipping, we care for the planet as much as for you.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-blue-600">
        <div class="container mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-white text-center">
            <div>
                <div class="text-4xl font-black mb-2">10M+</div>
                <div class="text-blue-100 text-sm uppercase tracking-widest">Happy Clients</div>
            </div>
            <div>
                <div class="text-4xl font-black mb-2">50+</div>
                <div class="text-blue-100 text-sm uppercase tracking-widest">Stores Worldwide</div>
            </div>
            <div>
                <div class="text-4xl font-black mb-2">150+</div>
                <div class="text-blue-100 text-sm uppercase tracking-widest">Global Designers</div>
            </div>
            <div>
                <div class="text-4xl font-black mb-2">100%</div>
                <div class="text-blue-100 text-sm uppercase tracking-widest">Quality Assurance</div>
            </div>
        </div>
    </section>

    <section class="py-20 container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 italic">The Minds Behind the Style</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
            <div class="group">
                <div class="relative overflow-hidden rounded-2xl mb-4">
                    <img src="https://i.pravatar.cc/400?u=1" class="w-full h-80 object-cover grayscale group-hover:grayscale-0 transition duration-500" alt="Founder">
                </div>
                <h4 class="text-xl font-bold text-gray-900">Alex Rivers</h4>
                <p class="text-blue-600">CEO & Founder</p>
            </div>
            <div class="group">
                <div class="relative overflow-hidden rounded-2xl mb-4">
                    <img src="https://i.pravatar.cc/400?u=2" class="w-full h-80 object-cover grayscale group-hover:grayscale-0 transition duration-500" alt="Creative Director">
                </div>
                <h4 class="text-xl font-bold text-gray-900">Sarah Jenkins</h4>
                <p class="text-blue-600">Creative Director</p>
            </div>
            <div class="group">
                <div class="relative overflow-hidden rounded-2xl mb-4">
                    <img src="https://i.pravatar.cc/400?u=3" class="w-full h-80 object-cover grayscale group-hover:grayscale-0 transition duration-500" alt="Lead Designer">
                </div>
                <h4 class="text-xl font-bold text-gray-900">Marcus Thorn</h4>
                <p class="text-blue-600">Lead Designer</p>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-900 text-white text-center">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-4xl font-bold mb-6 italic italic">Ready to transform your look?</h2>
            <p class="text-gray-400 mb-10 text-lg">Join 500k+ subscribers and get 15% off your first purchase.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <input type="email" placeholder="Enter your email" class="px-6 py-4 rounded-full bg-gray-800 border-none focus:ring-2 focus:ring-blue-600 w-full sm:w-96">
                <button class="bg-blue-600 hover:bg-blue-700 px-10 py-4 rounded-full font-bold transition duration-300">Join Now</button>
            </div>
        </div>
    </section>
</main>
@endsection