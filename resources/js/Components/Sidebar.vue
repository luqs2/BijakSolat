<!-- resources/js/Components/Sidebar.vue -->
<template>
  <div class="relative min-h-screen">
    <!-- Backdrop for mobile -->
    <div v-if="isMobileOpen" @click="toggleMobile" class="lg:hidden fixed inset-0 bg-black/50 z-30"></div>

    <!-- Sidebar -->
    <aside :class="`
      fixed top-0 left- h-screen z-50
      ${isMobileOpen ? 'translate-x-0' : '-translate-x-full'}
      lg:translate-x-0
      ${isExpanded ? 'w-60' : 'w-17'}
      transition-all duration-300 ease-in-out
      bg-mint-50 text-mint-900
      flex flex-col
    `">
      <!-- Mobile Menu Button -->
      <button @click="toggleMobile"
        class="lg:hidden absolute -right-7 top-4 p-2.5 rounded-lg bg-mint-50 shadow-md z-50">
        <i :class="[
          'fas',
          isMobileOpen ? 'fa-chevron-left' : 'fa-chevron-right',
          'h-5 w-5 text-mint-800'
        ]"></i>
      </button>

      <!-- Profile Section or Login Button -->
      <div class="flex-shrink-0 p-4 border-b border-mint-200">
        <template v-if="isAuthenticated">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-mint-200 flex items-center justify-center overflow-hidden">
              <img :src="userAvatar || fallbackAvatar" :alt="userName" class="w-full h-full object-cover rounded-full"
                @error="handleImageError" />
            </div>
            <div v-if="isExpanded" class="flex-1">
              <h3 class="font-medium">{{ userName }}</h3>
              <p class="text-sm text-mint-600">{{ userRole }}</p>
            </div>
          </div>
        </template>
        <template v-else>
          <Link href="/login"
            class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-mint-600 hover:bg-mint-700 text-white rounded-lg transition-colors">
          <i class="fas fa-sign-in-alt"></i>
          <span v-if="isExpanded">Log Masuk</span>
          </Link>
        </template>
      </div>

      <!-- Toggle Button (only show if authenticated) -->
      <button v-if="isAuthenticated" @click="toggleSidebar"
        class="hidden lg:block absolute -right-3 top-16 p-1.5 rounded-full bg-mint-200 text-mint-600">
        <i :class="[
          'fas',
          isExpanded ? 'fa-chevron-left' : 'fa-chevron-right',
          'h-4 w-4'
        ]"></i>
      </button>

      <!-- Main Menu -->
      <div class="flex-1 overflow-y-auto p-3">
        <p class="px-3 py-2 text-xs font-medium text-mint-600">MAIN</p>
        <nav class="space-y-1">
          <template v-if="isAuthenticated">
            <Link v-for="item in menuItems" :key="item.name" :href="item.path" :class="`
                    flex items-center gap-3 px-3 py-2 rounded-lg
                    ${isCurrentRoute(item.path) ? 'bg-mint-200' : 'hover:bg-mint-100'}
                    text-mint-700
                  `">
            <i :class="[item.icon, 'h-5 w-5']"></i>
            <span v-if="isExpanded">{{ item.name }}</span>
            </Link>
          </template>
          <template v-else>
            <!-- Guest Menu Items -->
            <Link v-for="item in guestMenuItems" :key="item.name" :href="item.path" :class="`
                    flex items-center gap-3 px-3 py-2 rounded-lg
                    ${isCurrentRoute(item.path) ? 'bg-mint-200' : 'hover:bg-mint-100'}
                    text-mint-700
                  `">
            <i :class="[item.icon, 'h-5 w-5']"></i>
            <span v-if="isExpanded">{{ item.name }}</span>
            </Link>
          </template>
        </nav>
      </div>

      <!-- Bottom Menu -->
      <div class="flex-shrink-0 p-3 border-t border-mint-200">
        <p class="px-1 py-2 text-xs font-medium text-mint-600">TETAPAN</p>
        <nav class="space-y-1">
          <template v-if="isAuthenticated">
            <template v-for="item in bottomMenuItems" :key="item.name">
              <Link v-if="item.path" :href="item.path" :class="`flex items-center gap-3 px-4 py-3 rounded-lg
                      ${isCurrentRoute(item.path) ? 'bg-mint-200' : 'hover:bg-mint-100'}
                      text-mint-700`">
              <i :class="[item.icon, 'h-5 w-5']"></i>
              <span v-if="isExpanded">{{ item.name }}</span>
              </Link>
              <button v-else @click="handleMenuItemClick(item)"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-mint-100 text-mint-700">
                <i :class="[item.icon, 'h-5 w-5']"></i>
                <span v-if="isExpanded">{{ item.name }}</span>
              </button>
            </template>
          </template>
          <template v-else>
            <Link v-for="item in guestBottomMenuItems" :key="item.name" :href="item.path" :class="`
                    flex items-center gap-3 px-4 py-3 rounded-lg
                    ${isCurrentRoute(item.path) ? 'bg-mint-200' : 'hover:bg-mint-100'}
                    text-mint-700
                  `">
            <i :class="[item.icon, 'h-5 w-5']"></i>
            <span v-if="isExpanded">{{ item.name }}</span>
            </Link>
          </template>
        </nav>
      </div>
    </aside>

    <!-- Main Content -->
    <main :class="`
      min-h-screen
      ${isExpanded ? 'lg:ml-64' : 'lg:ml-16'}
      transition-all duration-300 ease-in-out
    `">
      <div class="p-6">
        <slot></slot>
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const userAvatar = computed(() => page.props.auth.user?.avatar || '/images/default-avatar.png');
const userName = computed(() => page.props.auth.user?.name || 'User');

const handleImageError = (e) => {
  e.target.src = '/images/default-avatar.png';
};
</script>

<script>
import { Link, useForm } from '@inertiajs/vue3';

export default {
  name: 'Sidebar',

  components: {
    Link
  },

  props: {
    isAuthenticated: {
      type: Boolean,
      default: true
    },
    userRole: {
      type: String,
      default: ''
    },
    user: {
      type: Object,
      default: null
    }
  },

  setup() {
    const form = useForm({});

    const logout = () => {
      form.post(route('logout'));
    };

    return { logout };
  },

  data() {
    return {
      isExpanded: true,
      isMobileOpen: false,
      menuItems: [
        { name: 'Dashboard', path: '/dashboard', icon: 'fas fa-home' },
        { name: 'Kemaskini', path: '/kemaskini', icon: 'fas fa-edit' },
        //   { name: 'Tahun', path: '/tahun', icon: 'fas fa-book' },
        { name: 'Guru', path: '/guru', icon: 'fas fa-users' },
        // { name: 'Pautan', path: '/pautan', icon: 'fas fa-calendar' },
        //   { name: 'Jadual', path: '/jadual', icon: 'fas fa-calendar-alt' },
        { name: 'Statistik', path: '/statistik', icon: 'fas fa-chart-bar' },
        { name: 'Objek Penilaian', path: '/objek-penilaian', icon: 'fas fa-tasks' }
      ],
      guestMenuItems: [
        { name: 'Home', path: '/', icon: 'fas fa-home' },
        // { name: 'About', path: '/about', icon: 'fas fa-info-circle' },
        // { name: 'Contact', path: '/contact', icon: 'fas fa-envelope' }
      ],
      bottomMenuItems: [
        { name: 'Tetapan', path: '/tetapan', icon: 'fas fa-cog' },
        //{ name: 'Bantuan', path: '/bantuan', icon: 'fas fa-question-circle' },
        { name: 'Log Keluar', path: '/logout', icon: 'fas fa-sign-out-alt' }
      ],
      guestBottomMenuItems: [
        // { name: 'Bantuan', path: '/bantuan', icon: 'fas fa-question-circle' },
        { name: 'Daftar', path: '/register', icon: 'fas fa-user-plus' }
      ],
      fallbackAvatar: '/default-avatar.jpg'
    }
  },

  methods: {
    toggleSidebar() {
      this.isExpanded = !this.isExpanded;
      // Force layout recalculation
      document.body.offsetHeight;
    },

    toggleMobile() {
      this.isMobileOpen = !this.isMobileOpen
    },

    isCurrentRoute(path) {
      return window.location.pathname === path
    },

    handleMenuItemClick(item) {
      if (item.action === 'logout') {
        this.logout();
      }
    },

    handleImageError(e) {
      e.target.src = this.fallbackAvatar;
    }
  },

  mounted() {
    document.addEventListener('click', (e) => {
      if (
        this.isMobileOpen &&
        !e.target.closest('aside') &&
        !e.target.closest('button')
      ) {
        this.isMobileOpen = false
      }
    })
  },

  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
  }
}
</script>

<style>
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}
</style>
