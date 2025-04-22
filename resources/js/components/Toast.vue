<script setup lang="ts">
import { watch } from 'vue';
import { useToast } from 'vue-toastification';
import { usePage } from '@inertiajs/vue3'; // <-- Import manquant

const toast = useToast();
const page = usePage(); // <-- Accès aux props


console.log('TOAST')

const props = defineProps<{
  flash?: {
    success?: string;
    error?: string;
  };
}>();

watch(
  () => props.flash || page.props.flash,
  (newFlashValues) => { // Paramètre renommé pour clarté
    if (newFlashValues.success) {
        toast.success(newFlashValues.success, {
            position: "top-right",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false
        });
    }
    if (newFlashValues.error) {
        toast.error(newFlashValues.error, {
            position: "top-right",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false
        });
    }
  },
  { deep: true, immediate: true } // Déclenche au montage du composant
);

</script>
