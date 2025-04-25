<script setup lang="ts">
import { onMounted, watch, nextTick } from 'vue'
import gsap from 'gsap'

// Props
const props = defineProps < {
    skills: Array < {
        id: number;
        label: string;
    } > ;
} > ()

// Fonction pour appliquer les animations
const animateItems = () => {
    nextTick(() => {
        const items = document.querySelectorAll('.hover-animate-item')
        items.forEach((el) => {
            el.addEventListener('mouseenter', () => {
                gsap.to(el, {
                    scale: 1.05,
                    boxShadow: '0px 10px 20px rgba(0, 0, 0, 0.4)',
                    duration: 0.3,
                })
            })
            el.addEventListener('mouseleave', () => {
                gsap.to(el, {
                    scale: 1,
                    boxShadow: '0px 0px 0px rgba(0, 0, 0, 0)',
                    duration: 0.3,
                })
            })
        })
    })
}

// Si les skills sont déjà là au montage
onMounted(() => {
    if (props.skills.length) {
        animateItems()
    }
})

// Si les skills arrivent après (ex: chargés dynamiquement)
watch(() => props.skills, (newSkills) => {
    if (newSkills.length) {
        animateItems()
    }
})
</script>

<template>
    <section id="skills" class="px-6 py-20 bg-[#112240] text-[#ccd6f6] hover-animate transition-transform duration-300 ease-in-out" data-aos="zoom-in">
        <h2 class="text-3xl font-bold mb-10 border-b-2 border-green-400 inline-block">Compétences</h2>
        <ul class="grid grid-cols-2 gap-4">
            <li v-for="skill in skills" :key="skill.id" class="bg-[#0a192f] p-4 rounded shadow hover-animate-item">{{ skill.label}}</li>
        </ul>
    </section>
</template>
