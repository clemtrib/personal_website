<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { computed, ref } from 'vue';
import Toast from '../components/Toast.vue';
import axios from 'axios';

const props = defineProps({
    page: {
        type: Object,
        default: () => null
    }
});

const seo = JSON.parse(props.page?.page_seo);

const form = useForm({
    id: props.page?.id || '',
    page_slug: props.page?.page_slug || '',
    page_name: props.page?.page_name || '',
    hero_subtitle: props.page?.hero_subtitle || '',
    hero_title: props.page?.hero_title || '',
    hero_description: props.page?.hero_description || '',
    hero_image: null,
    content_text: props.page?.content_text || '',
    content_image: null,
    seo_description: seo?.description || '',
    seo_og_title: seo && Object.hasOwn(seo, "og:title") ? seo['og:title'] : '',
    seo_og_description: seo && Object.hasOwn(seo, "og:title") ? seo['og:description'] : '',
    seo_twitter_title: seo && Object.hasOwn(seo, "og:title") ? seo['twitter:title'] : '',
    seo_twitter_description: seo && Object.hasOwn(seo, "og:title") ? seo['twitter:description'] : '',
}, {
    forceFormData: true
});

const isEditMode = computed(() => !!props.page?.id);
const imageHeroPreview = ref < string | null > (null);
const imagePreview = ref < string | null > (null);
const hasExistingHeroImage = ref(!!props.page?.hero_image);
const hasExistingImage = ref(!!props.page?.content_image);
const removeExistingHeroImage = ref(false);
const removeExistingImage = ref(false);

const handleHeroFileChange = (e: Event) => {
    const input = e.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        form.hero_image = file;
        imageHeroPreview.value = URL.createObjectURL(file);
        removeExistingHeroImage.value = false;
    } else {
        form.hero_image = null;
        imageHeroPreview.value = null;
    }
};

const handleFileChange = (e: Event) => {
    const input = e.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        form.content_image = file;
        imagePreview.value = URL.createObjectURL(file);
        removeExistingImage.value = false;
    } else {
        form.content_image = null;
        imagePreview.value = null;
    }
};

const handleHeroRemoveImage = () => {
    form.hero_image = null;
    imageHeroPreview.value = null;
    if (hasExistingHeroImage.value) {
        removeExistingHeroImage.value = true;
    }
};

const handleRemoveImage = () => {
    form.content_image = null;
    imagePreview.value = null;
    if (hasExistingImage.value) {
        removeExistingImage.value = true;
    }
};

const submit = () => {
    if (form.hero_description.trim() === '<p></p>' || form.hero_description.trim() === '<p><br></p>') {
        form.setError('hero_description', 'Le champ Texte est obligatoire');
        return;
    }

    const formData = new FormData();
    Object.entries(form.data()).forEach(([key, value]) => {
        if (value !== null && value !== undefined) {
            formData.append(key, value);
        }
    });

    if (isEditMode.value) {
        formData.append('_method', 'PUT');
    }

    if (removeExistingImage.value) {
        formData.append('remove_content_image', '1');
    }

    if (removeExistingHeroImage.value) {
        formData.append('remove_hero_image', '1');
    }

    axios.post(
            isEditMode.value ? route('pages.update', props.page.id) : route('pages.store'),
            formData, { headers: { 'Content-Type': 'multipart/form-data' } }
        )
        .then(response => {
            localStorage.setItem('flashMessage', response.data.message);
            localStorage.setItem('flashType', 'success');
            window.location.href = route('pages');
        }).catch(error => {
            const message = error.response?.data?.message || 'Erreur inconnue';
            localStorage.setItem('flashMessage', message);
            localStorage.setItem('flashType', 'error');
            window.location.href = route('pages');
        });
};

const openSection = ref('hero');
const toggleSection = (section: string) => {
    openSection.value = openSection.value === section ? '' : section;
};

const page = usePage();
</script>

<template>
    <Toast :flash="page.props.flash" />
    <Head :title="isEditMode ? 'Modifier une page' : 'Ajouter une page'" />

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form @submit.prevent="submit" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-8">
            <!-- Colonne gauche : formulaire -->
            <div class="flex-1">

                <!-- HERO -->
                <fieldset class="border border-gray-300 dark:border-gray-600 rounded mb-6 overflow-hidden transition-all">
                    <legend class="mx-10 px-2 py-2 cursor-pointer text-lg" @click="toggleSection('page')">
                        Page
                    </legend>
                    <Transition name="accordion">
                        <div v-show="openSection === 'page'" class="p-4 space-y-4">
                            <div>
                                <div>
                                    <Label for="page_slug">Slug</Label>
                                    <Input id="page_slug" type="text" v-model="form.page_slug" required />
                                    <p v-if="form.errors.page_slug" class="text-red-600 text-sm">{{ form.errors.page_slug }}</p>
                                </div>

                                <div>
                                    <Label for="page_name">Nom</Label>
                                    <Input id="page_name" type="text" v-model="form.page_name" required />
                                    <p v-if="form.errors.page_name" class="text-red-600 text-sm">{{ form.errors.page_name }}</p>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </fieldset>

                <!-- HERO -->
                <fieldset class="border border-gray-300 dark:border-gray-600 rounded mb-6 overflow-hidden transition-all">
                    <legend class="mx-10 px-2 py-2 cursor-pointer text-lg" @click="toggleSection('hero')">
                        Entête
                    </legend>
                    <Transition name="accordion">
                        <div v-show="openSection === 'hero'" class="p-4 space-y-4">

                            <div>
                                <Label for="hero_subtitle">Surtitre</Label>
                                <Input id="hero_subtitle" type="text" v-model="form.hero_subtitle" />
                                <p v-if="form.errors.hero_subtitle" class="text-red-600 text-sm">{{ form.errors.hero_subtitle }}</p>
                            </div>

                            <div>
                                <Label for="hero_title">Titre</Label>
                                <Input id="hero_title" type="text" v-model="form.hero_title" />
                                <p v-if="form.errors.hero_title" class="text-red-600 text-sm">{{ form.errors.hero_title }}</p>
                            </div>

                            <div>
                                <Label for="hero_description">Texte</Label>
                                <QuillEditor theme="snow" id="hero_description" v-model:content="form.hero_description" contentType="html" class="custom-quill-style" />
                                <p v-if="form.errors.hero_description" class="text-red-600 text-sm">{{ form.errors.hero_description }}</p>
                            </div>

                            <div>
                                <Label for="hero_image">Image</Label>
                                <input id="hero_image" type="file" accept="image/*" @change="handleHeroFileChange" class="block w-full text-sm text-gray-500
                                               file:mr-4 file:py-2 file:px-4
                                               file:rounded-md file:border-0
                                               file:text-sm file:font-semibold
                                               file:bg-gray-50 file:text-gray-700
                                               hover:file:bg-gray-100" />
                                <p v-if="form.errors.hero_image" class="text-red-600 text-sm">{{ form.errors.hero_image }}</p>
                            </div>
                        </div>
                    </Transition>
                </fieldset>

                <!-- Content -->
                <fieldset class="border border-gray-300 dark:border-gray-600 rounded mb-6 overflow-hidden transition-all">
                    <legend class="mx-10 px-2 py-2 cursor-pointer text-lg" @click="toggleSection('content')">
                        Contenu
                    </legend>
                    <Transition name="accordion">
                        <div v-show="openSection === 'content'" class="p-4 space-y-4">

                            <div>
                                <Label for="content_text">Texte</Label>
                                <QuillEditor theme="snow" id="content_text" v-model:content="form.content_text" contentType="html" class="custom-quill-style" />
                                <p v-if="form.errors.content_text" class="text-red-600 text-sm">{{ form.errors.content_text }}</p>
                            </div>

                            <div>
                                <Label for="content_image">Image</Label>
                                <input id="content_image" type="file" accept="image/*" @change="handleFileChange" class="block w-full text-sm text-gray-500
                                               file:mr-4 file:py-2 file:px-4
                                               file:rounded-md file:border-0
                                               file:text-sm file:font-semibold
                                               file:bg-gray-50 file:text-gray-700
                                               hover:file:bg-gray-100" />
                                <p v-if="form.errors.content_image" class="text-red-600 text-sm">{{ form.errors.content_image }}</p>
                            </div>
                        </div>
                    </Transition>
                </fieldset>

                <fieldset class="border border-gray-300 dark:border-gray-600 rounded mb-6 overflow-hidden transition-all">
                    <legend class="mx-10 px-2 py-2 cursor-pointer text-lg" @click="toggleSection('seo')">
                        SEO
                    </legend>
                    <Transition name="accordion">
                        <div v-show="openSection === 'seo'" class="p-4 space-y-4">
                            <div>

                            <div>
                                <Label for="seo_description">Description</Label>
                                <Input id="seo_description" type="text" v-model="form.seo_description" />
                                <p v-if="form.errors.seo_description" class="text-red-600 text-sm">{{ form.errors.seo_description }}</p>
                            </div>

                            <div>
                                <Label for="seo_og_title">Facebook - titre</Label>
                                <Input id="seo_og_title" type="text" v-model="form.seo_og_title" />
                                <p v-if="form.errors.seo_og_title" class="text-red-600 text-sm">{{ form.errors.seo_og_title }}</p>
                            </div>

                            <div>
                                <Label for="seo_og_description">Facebook - description</Label>
                                <Input id="seo_og_description" type="text" v-model="form.seo_og_description" />
                                <p v-if="form.errors.seo_og_description" class="text-red-600 text-sm">{{ form.errors.seo_og_description }}</p>
                            </div>

                            <div>
                                <Label for="seo_twitter_title">Twitter - titre</Label>
                                <Input id="seo_twitter_title" type="text" v-model="form.seo_twitter_title" />
                                <p v-if="form.errors.seo_twitter_title" class="text-red-600 text-sm">{{ form.errors.seo_twitter_title }}</p>
                            </div>

                            <div>
                                <Label for="seo_twitter_description">Twitter - description</Label>
                                <Input id="seo_twitter_description" type="text" v-model="form.seo_twitter_description" />
                                <p v-if="form.errors.seo_twitter_description" class="text-red-600 text-sm">{{ form.errors.seo_twitter_description }}</p>
                            </div>

                            </div>
                        </div>
                    </Transition>
                </fieldset>

                <div class="mt-6">
                    <Button type="submit" :disabled="form.processing">
                            {{ isEditMode ? 'Mettre à jour' : 'Créer' }}
                        </Button>
                </div>
            </div>

            <!-- Colonne droite : prévisualisation -->
            <div class="w-full lg:w-1/4">

                <div v-if="imageHeroPreview" class="mb-4 border rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">Nouvelle image (hero) :</p>
                    <img :src="imageHeroPreview" alt="Aperçu" class="w-full rounded-md shadow border mb-2" />
                    <Button type="button" variant="destructive" @click="handleHeroRemoveImage">Supprimer l'image</Button>
                </div>

                <div v-else-if="hasExistingHeroImage && props.page?.hero_image" class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">Image actuelle (hero) :</p>
                    <img :src="`/storage/${props.page.hero_image}`" alt="Image actuelle" class="w-full rounded-md shadow border mb-2" />
                    <Button type="button" variant="destructive" @click="handleHeroRemoveImage">Supprimer l'image</Button>
                </div>

                <div v-if="imagePreview" class="mb-4 border rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">Nouvelle image (contenu) :</p>
                    <img :src="imagePreview" alt="Aperçu" class="w-full rounded-md shadow border mb-2" />
                    <Button type="button" variant="destructive" @click="handleRemoveImage">Supprimer l'image</Button>
                </div>

                <div v-else-if="hasExistingImage && props.page?.content_image" class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">Image actuelle (contenu) :</p>
                    <img :src="`/storage/${props.page.content_image}`" alt="Image actuelle" class="w-full rounded-md shadow border mb-2" />
                    <Button type="button" variant="destructive" @click="handleRemoveImage">Supprimer l'image</Button>
                </div>
            </div>
        </form>
    </div>
</template>

<style>
.custom-quill-style {
    background: white;
    border-radius: 0.375rem;
    border: 1px solid #d1d5db;
    min-height: 200px;
    color: black;
}

.dark .custom-quill-style {
    background: #374151;
    border-color: #4b5563;
    color: white;
}

.dark .ql-editor {
    color: white;
}

.ql-editor.ql-blank::before {
    color: #9ca3af;
}

.dark .ql-editor.ql-blank::before {
    color: #6b7280;
}

/* Transition accordéon */

.accordion-enter-active,
.accordion-leave-active {
    transition: max-height 0.3s ease, opacity 0.3s ease, padding 0.3s ease;
    overflow: hidden;
}

.accordion-enter-from,
.accordion-leave-to {
    max-height: 0;
    opacity: 0;
    padding-top: 0;
    padding-bottom: 0;
}

.accordion-enter-to,
.accordion-leave-from {
    max-height: 1000px;
    opacity: 1;
}
</style>
