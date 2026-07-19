<x-app-layout>
    <x-slot name="header">
        Product Catalog Manager
    </x-slot>

    <!-- Alpine Wrapper for Modal Management -->
    <div x-data="{ 
        modalOpen: false, 
        isEdit: false,
        actionUrl: '',
        productId: '',
        productName: '',
        productSlug: '',
        productCategory: '',
        productVersion: '',
        productEnvatoId: '',
        productShortDesc: '',
        productDesc: '',
        productDemoUrl: '',
        productBuyUrl: '',
        productDocsUrl: '',
        productActive: '1',
        productImageUrl: '',
        productChannels: {},

        openCreate() {
            this.isEdit = false;
            this.actionUrl = '{{ route('admin.products.store') }}';
            this.productId = '';
            this.productName = '';
            this.productSlug = '';
            this.productCategory = '';
            this.productVersion = '1.0.0';
            this.productEnvatoId = '';
            this.productShortDesc = '';
            this.productDesc = '';
            this.productDemoUrl = '';
            this.productBuyUrl = '';
            this.productDocsUrl = '';
            this.productActive = '1';
            this.productImageUrl = '';
            
            // Initialize default channel values
            this.productChannels = {};
            @foreach($channels as $chan)
                this.productChannels[{{ $chan->id }}] = { enabled: false, purchase_url: '', price: '', priority: 0, external_product_id: '' };
            @endforeach

            this.modalOpen = true;
        },

        openEdit(p) {
            this.isEdit = true;
            this.actionUrl = '/admin/products/' + p.id;
            this.productId = p.id;
            this.productName = p.name;
            this.productSlug = p.slug;
            this.productCategory = p.product_category_id;
            this.productVersion = p.version;
            this.productEnvatoId = p.envato_item_id || '';
            this.productShortDesc = p.short_description || '';
            this.productDesc = p.description || '';
            this.productDemoUrl = p.demo_url || '';
            this.productBuyUrl = p.buy_url || '';
            this.productDocsUrl = p.docs_url || '';
            this.productActive = p.is_active ? '1' : '0';
            this.productImageUrl = p.image_url || '';

            // Reset and load channel values from pivot data
            this.productChannels = {};
            @foreach($channels as $chan)
                this.productChannels[{{ $chan->id }}] = { enabled: false, purchase_url: '', price: '', priority: 0, external_product_id: '' };
            @endforeach
            
            if (p.sales_channels) {
                p.sales_channels.forEach(ch => {
                    this.productChannels[ch.id] = {
                        enabled: ch.pivot.status === 'active',
                        purchase_url: ch.pivot.purchase_url || '',
                        price: ch.pivot.price || '',
                        priority: ch.pivot.priority || 0,
                        external_product_id: ch.pivot.external_product_id || '',
                    };
                });
            }

            this.modalOpen = true;
        },

        generateSlug() {
            this.productSlug = this.productName
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    }" class="space-y-6">

        <!-- Top Actions Banner -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-outfit font-bold text-slate-900 dark:text-white text-base">Catalog Registry</h2>
                <p class="text-xs text-slate-500">Configure public products, licensing configurations, and document redirections.</p>
            </div>
            <button @click="openCreate()" class="px-4 py-2 bg-primary hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                Register New Product
            </button>
        </div>

        <!-- Products Table Grid -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-3.5">Product Name</th>
                            <th class="px-6 py-3.5">Category</th>
                            <th class="px-6 py-3.5">Envato Item ID</th>
                            <th class="px-6 py-3.5">Current Version</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        @forelse ($products as $p)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($p->image_url)
                                            <img src="{{ $p->image_url }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-900" alt="{{ $p->name }}">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-950 text-slate-400 flex items-center justify-center border border-slate-200 dark:border-slate-800 font-bold text-xs">
                                                N/A
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $p->name }}</div>
                                            <div class="text-[11px] text-slate-500 font-mono mt-0.5">{{ $p->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $p->category->name }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                    {{ $p->envato_item_id ?: 'No Envato Binding' }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                    v{{ $p->version }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($p->is_active)
                                        <x-badge color="green">Active</x-badge>
                                    @else
                                        <x-badge color="gray">Inactive</x-badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2.5">
                                    <button @click="openEdit({{ $p->toJson() }})" class="p-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg transition-colors flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product from the catalog? This will delete associated documentation categories and articles.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/50 text-red-600 rounded-lg transition-colors flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500">
                                    No products cataloged in database yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- AlpineJS Modal Backdrop & Content -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4" style="display: none;">
            <!-- Backdrop -->
            <div @click="modalOpen = false" class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Content Card -->
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-2xl z-10 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-950/20">
                    <h3 class="font-outfit font-bold text-slate-950 dark:text-white text-base" x-text="isEdit ? 'Modify Product Specifications' : 'Register New Product'"></h3>
                    <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Product Name</label>
                            <input type="text" id="name" name="name" x-model="productName" @input="generateSlug()" required
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Slug Identifier</label>
                            <input type="text" id="slug" name="slug" x-model="productSlug" required
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="product_category_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Category</label>
                            <select id="product_category_id" name="product_category_id" x-model="productCategory" required
                                    class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Version -->
                        <div>
                            <label for="version" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Initial Version</label>
                            <input type="text" id="version" name="version" x-model="productVersion" required placeholder="1.0.0"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Envato Item ID -->
                        <div>
                            <label for="envato_item_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Envato Item ID</label>
                            <input type="text" id="envato_item_id" name="envato_item_id" x-model="productEnvatoId" placeholder="e.g. 12345678"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="is_active" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Catalog Status</label>
                            <select id="is_active" name="is_active" x-model="productActive" required
                                    class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                                <option value="1">Active (Published)</option>
                                <option value="0">Inactive (Draft)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Short Description -->
                    <div>
                        <label for="short_description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Short Description</label>
                        <input type="text" id="short_description" name="short_description" x-model="productShortDesc" required placeholder="A brief one-line description of the product..."
                               class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                    </div>

                    <!-- Full Description -->
                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Detailed Description</label>
                        <textarea id="description" name="description" x-model="productDesc" rows="3" required placeholder="Full product specifications and markdown outline details..."
                                  class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary"></textarea>
                    </div>

                    <!-- Product Image (Local Upload Only) -->
                    <div>
                        <label for="image" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Product Feature Image (Local Upload)</label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="block w-full rounded-lg border border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-2.5 px-3 focus:ring-primary focus:border-primary">
                        <p class="text-[10px] text-slate-400 mt-1">Select a PNG, JPG, or WEBP image file to display on the homepage cards (Max 2MB).</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Demo URL -->
                        <div>
                            <label for="demo_url" class="block text-xs text-slate-500 mb-1">Live Demo URL</label>
                            <input type="text" id="demo_url" name="demo_url" x-model="productDemoUrl" placeholder="https://..."
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Buy URL -->
                        <div>
                            <label for="buy_url" class="block text-xs text-slate-500 mb-1">Envato Buy URL</label>
                            <input type="text" id="buy_url" name="buy_url" x-model="productBuyUrl" placeholder="https://..."
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Docs Path -->
                        <div>
                            <label for="docs_url" class="block text-xs text-slate-500 mb-1">Docs Path Redirect</label>
                            <input type="text" id="docs_url" name="docs_url" x-model="productDocsUrl" placeholder="docs/product-slug"
                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-sm py-2 px-3 focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <!-- Marketplace & Sales Channels Settings -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Marketplace & Sales Channels</h4>
                        
                        <div class="space-y-3">
                            @foreach ($channels as $chan)
                                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3" x-data="{ enabled: false }" x-init="$watch('productChannels[{{ $chan->id }}]?.enabled', value => enabled = !!value)">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="channels[{{ $chan->id }}][enabled]" value="1"
                                                   id="channel_enabled_{{ $chan->id }}"
                                                   x-model="productChannels[{{ $chan->id }}] && productChannels[{{ $chan->id }}].enabled"
                                                   class="rounded border-slate-300 dark:border-slate-700 text-primary focus:ring-primary">
                                            <label for="channel_enabled_{{ $chan->id }}" class="text-sm font-semibold text-slate-850 dark:text-slate-200 cursor-pointer">
                                                Enable on {{ $chan->name }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="productChannels[{{ $chan->id }}] && productChannels[{{ $chan->id }}].enabled" style="display: none;">
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Purchase URL</label>
                                            <input type="url" name="channels[{{ $chan->id }}][purchase_url]" 
                                                   x-model="productChannels[{{ $chan->id }}] && productChannels[{{ $chan->id }}].purchase_url"
                                                   placeholder="https://..."
                                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2.5">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Price ($)</label>
                                            <input type="number" step="0.01" min="0" name="channels[{{ $chan->id }}][price]" 
                                                   x-model="productChannels[{{ $chan->id }}] && productChannels[{{ $chan->id }}].price"
                                                   placeholder="59.00"
                                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2.5">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">External Product ID (Optional)</label>
                                            <input type="text" name="channels[{{ $chan->id }}][external_product_id]" 
                                                   x-model="productChannels[{{ $chan->id }}] && productChannels[{{ $chan->id }}].external_product_id"
                                                   placeholder="e.g. CodeCanyon Item ID"
                                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2.5">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase mb-1">Priority Order</label>
                                            <input type="number" name="channels[{{ $chan->id }}][priority]" 
                                                   x-model="productChannels[{{ $chan->id }}] && productChannels[{{ $chan->id }}].priority"
                                                   placeholder="0"
                                                   class="block w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1.5 px-2.5">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-850 hover:bg-slate-200 text-slate-700 dark:text-slate-350 text-sm font-semibold rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-primary text-white text-sm font-bold rounded-lg shadow-md hover:opacity-90">
                            Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
