<script setup>
import { Link } from '@inertiajs/inertia-vue3';
const machines = defineProps({
    olympus : {
        type:String,
    },
    eddfyi : {
        type:String
    }
});
</script>
<template>
  <div class="flex h-[300px]">
                    <!-- Left Column -->
                    <div class="w-1/5 flex items-center justify-center">
                        <h2 class="text-2xl font-semibold mb-4 ">Select Machine</h2>
                    </div>

                    <!-- Right Column -->
                    <div class="w-full flex items-center justify-center border-black">

                        <div class="flex w-full">
                            <Link class="w-1/2" :href="route('component',machines.olympus)"> <div class="card bg-[#DEE791] rounded-box grid h-50 grow place-items-center text-5xl">Olympus</div></Link>



                            <div class="divider divider-horizontal">OR</div>
                            <Link class="w-1/2" :href="route('component',machines.eddfyi)">
                                <div class="card bg-[#A5B68D]  rounded-box grid h-50 grow place-items-center text-5xl">Eddyfi-Gekko M2M</div>
                            </Link>
                        </div>
                    </div>
                </div>
</template>