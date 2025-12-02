<script setup>

import { Form, useForm, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const page = usePage();
const machine = defineProps({
    name: {
        type: Number
    }
});
const uploadProgress = ref(0);
const data_verification_fields = reactive({
    pipe_od: '0.00',
    wt_mm: '0.00',
    support_type: '',
    probe_model: '',
    wedge_model: '',
    flaw_law: '',
    element_qty: 0,
    min_angle: '0.00',
    max_angle: '0.00',
    angle_step: '0.00',
    pcs_mm: '0',
    pcs_wt: '0',
    scan_direction: '',
    scan_step: '0.00',
    scan_start: '0.00',
    scan_end: '0.00',
    /*  pc: '',
     cm1: '',
     cm2: '' */

});
const form = useForm({
    data_verfication_disable: false,
    data_run_disable: false,
    pc_btn_disable: false,
    cm1_btn_disable: false,
    cm2_btn_disable: false,
    pc: '',
    cm1: '',
    cm2: '',
    pc_message: ''
})
const calculatepcswt = () => {
    data_verification_fields.pcs_wt = data_verification_fields.pcs_mm / data_verification_fields.wt_mm;
};
/* const submitForm = () => {
    uploadProgress.value = 0;

    form.post("/upload", {
        onProgress: (event) => {
            uploadProgress.value = Math.round(
                (event.loaded / event.total) * 100
            );
        },
        onSuccess: () => {
            uploadProgress.value = 100;
        },
        forceFormData: true, // important for multipart
    });
}; */
const uploadPCFile = ($e) => {
    form.pc = $e.target.files[0];

    form.post('/uploadPCFile', {
        forceFormData: true,
        onProgress: (event) => {
            uploadProgress.value = Math.round((event.loaded / event.total) * 100);
        },
        /* onSuccess: () => {
            const flash = usePage().props.flash;
            console.log(flash.status, flash.message);
        }, */
        onSuccess: () => {
            const flash = usePage().props.flash;
            console.log("flash:", flash);
            console.log("flash.message:", flash?.message);
        },
        onError: () => {
            const flash = usePage().props.flash;
            console.log("Error:", flash.message);
        }
    });
};
</script>

<template>

    <div class="grid grid-cols-2 gap-4">
        <!-- <form @submit.prevent="form.post('/login')"> -->
        <div>


            <div class="form-control w-full">
                <fieldset class="fieldset bg-base-100 border-base-300 rounded-box w-full border p-4">
                    <legend class="fieldset-legend">Component Details</legend>


                    <div class="grid grid-cols-3 gap-4">
                        <label class="floating-label">
                            <span>Pipe OD</span>
                            <input v-model="data_verification_fields.pipe_od" type="text" placeholder="Pipe OD (Inch)"
                                class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>WT (mm)</span>
                            <input @input="calculatepcswt" v-model="data_verification_fields.wt_mm" type="text"
                                placeholder="WT (mm)" class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>Support Type</span>
                            <input v-model="data_verification_fields.support_type" type="text"
                                placeholder="Support Type" class="input input-bordered input-md w-full" />
                        </label>
                    </div>
                </fieldset>
                <fieldset class="fieldset bg-base-100 border-base-300 rounded-box w-full border p-4">
                    <legend class="fieldset-legend">Probe Details</legend>
                    <div class="flex gap-3 mt-1">
                        <label class="floating-label">
                            <span>Probe Model</span>
                            <input v-model="data_verification_fields.probe_model" placeholder="Probe Model" type="text"
                                class="input input-bordered input-md w-full" />

                        </label>
                        <label class="floating-label">
                            <span>Wedge Model</span>
                            <input v-model="data_verification_fields.wedge_model" placeholder="Wedge Model" type="text"
                                class="input input-bordered input-md w-full" />
                        </label>

                    </div>
                </fieldset>
                <fieldset class="fieldset bg-base-100 border-base-300 rounded-box w-full border p-4">
                    <legend class="fieldset-legend">Inspection Details</legend>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="floating-label">
                            <span>Flaw law</span>
                            <input v-model="data_verification_fields.flaw_law" placeholder="Flaw Law" type="text"
                                class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>Element Qty</span>
                            <input v-model="data_verification_fields.element_qty" placeholder="Element Qty" type="text"
                                class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>Min Angle</span>
                            <input v-model="data_verification_fields.min_angle" placeholder="Min Angle" type="text"
                                class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>Max Angle</span>
                            <input v-model="data_verification_fields.max_angle" placeholder="Max Angle" type="text"
                                class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>Angle Step</span>
                            <input v-model="data_verification_fields.angle_step" placeholder="Angle Step" type="text"
                                class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>PCS (mm)</span>
                            <input v-model="data_verification_fields.pcs_mm" placeholder="PCS (mm)"
                                @input="calculatepcswt" type="text" class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>PCS (WT)</span>
                            <input disabled v-model="data_verification_fields.pcs_wt" placeholder="PCS (WT)" type="text"
                                class="input input-bordered input-md w-full bg-white  border-gray-500" />
                        </label>
                        <label class="floating-label">
                            <span>Scan Direction</span>
                            <input v-model="data_verification_fields.scan_direction" placeholder="Scan Direction"
                                type="text" class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>Scan Step</span>
                            <input v-model="data_verification_fields.scan_step" placeholder="Scan Step" type="text"
                                class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>Scan Start</span>
                            <input v-model="data_verification_fields.scan_start" placeholder="Scan Starts" type="text"
                                class="input input-bordered input-md w-full" />
                        </label>
                        <label class="floating-label">
                            <span>Scan End</span>
                            <input v-model="data_verification_fields.scan_end" placeholder="Scan End" type="text"
                                class="input input-bordered input-md w-full" />
                        </label>

                    </div>
                </fieldset>


            </div>


            <button class="btn mt-4 w-full  text-white btn-grad" :disable="form.data_verfication_disable">
                Data Verification
            </button>



        </div>
        <!--</form> -->
        <div>
            <form @submit.prevent="submitForm">
                <fieldset class="fieldset bg-base-200 border-base-300 h-40 rounded-box w-full border p-4">
                    <legend class="fieldset-legend">PC Files</legend>

                    <input type="file" class="file-input file-input-success" @change="uploadPCFile" />

                </fieldset>
                <fieldset class="fieldset bg-base-200 border-base-300 h-40 rounded-box w-full border p-4">
                    <legend class="fieldset-legend">CM1 Files</legend>
                    <input :disabled="form.cm1_btn_disable" type="file" @change="form.cm1 = $event.target.files[0]"
                        class="file-input file-input-info" />
                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                </fieldset>
                <fieldset class="fieldset bg-base-200 border-base-300 h-40 rounded-box w-full border p-4">
                    <legend class="fieldset-legend">CM2 Files</legend>
                    <input :disabled="form.cm2_btn_disable" type="file" @change="form.cm2 = $event.target.files[0]"
                        class="file-input file-input-accent" />
                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                </fieldset>
                <button class="btn btn-grad mt-4 w-full text-white">
                    Data Run
                </button>
            </form>

        </div>
    </div>

</template>
<style scoped>
.btn-grad {
    background-image: linear-gradient(to right, #232526 0%, #414345 51%, #232526 100%)
}

.btn-grad {
    text-align: center;
    text-transform: uppercase;
    transition: 0.5s;
    background-size: 200% auto;
    color: white;
    box-shadow: 0 0 20px #eee;
    display: block;
}

.btn-grad:hover {
    background-position: right center;
    /* change the direction of the change here */
    color: #fff;
    text-decoration: none;
}
</style>