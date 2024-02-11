<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

defineProps: ({
    isAdmin: Boolean,
    isManager: Boolean,
    isClerk: Boolean,
})

</script>

<template>
  <div class="sidebar bg-slate-500 w-[100%] h-[100%] flex justify-around">
      <!-- Your navigation links based on user role -->
      <NavLink v-if="isAdmin || isManager || isClerk" :href="route('staff.dashboard')" class="text-slate-300">Dashboard</NavLink>
      <NavLink v-if="isAdmin || isManager || isClerk" :href="route('reservations.index')" class="text-slate-300">Reservations</NavLink>
      <NavLink v-if="isAdmin || isManager || isClerk" :href="route('accommodations.index')" class="text-slate-300">Accommodations</NavLink>
      <NavLink v-if="isAdmin || isManager" :href="route('staff.index')" class="text-slate-300">Staff</NavLink>
      <!-- <NavLink v-if="isAdmin || isManager || isClerk" :href="route('guests.index')" class="text-slate-300">Guests</NavLink> -->
      <!-- <NavLink v-if="isAdmin || isManager" :href="route('staff.configurations')" class="text-slate-300">Configurations</NavLink> -->
      <!-- <NavLink :href="route('/profile')" class="text-slate-300">Profile</NavLink> -->
      <NavLink :href="route('logout')" class="text-slate-300">Logout</NavLink>
    </div>
</template>

<script>
export default {
  computed: {
        isAdmin() {
            return this.$page.props.auth.user.roles[0].name == 'admin';
        },
        isManager() {
            return this.$page.props.auth.user.roles[0].name === 'manager';
        },
        isClerk() {
            return this.$page.props.auth.user.roles[0].name === 'clerk';
        }
    },
}
</script>

<style scoped>
.sidebar {
  flex-direction: column;
  align-items: center;
}

.sidebar router-link {
  color: white;
  margin-bottom: 10px;
  text-decoration: none;
}

.content {
  flex: 1;
  padding: 20px;
}
</style>