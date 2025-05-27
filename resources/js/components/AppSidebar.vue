<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Briefcase, Calendar, CircleCheckBig, Earth, FileText, Folder, Gamepad2, GraduationCap, LayoutGrid, Mail, Receipt, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        url: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Pages',
        url: '/dashboard/pages/list',
        icon: FileText,
    },
    {
        title: 'Diplômes',
        url: '/dashboard/education/list',
        icon: GraduationCap,
    },
    {
        title: 'Compétences',
        url: '/dashboard/skills/list',
        icon: CircleCheckBig,
    },
    {
        title: 'Expériences',
        url: '/dashboard/experiences/list',
        icon: Briefcase,
    },
    {
        title: 'Loisirs',
        url: '/dashboard/hobbies/list',
        icon: Gamepad2,
    },
    {
        title: 'Messages',
        url: '/dashboard/messages/list',
        icon: Mail,
    },
    {
        title: 'Rencontres',
        url: '/dashboard/meets/list',
        icon: Calendar,
    },
    {
        title: 'Clients',
        url: '/dashboard/customers/list',
        icon: Users,
    },
    {
        title: 'Facturation',
        url: '/dashboard/bills/list',
        icon: Receipt,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/clemtrib/personal_website',
        icon: Folder,
    },
    {
        title: 'Website',
        href: 'https://www.clementtribouillard.com',
        icon: Earth,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
