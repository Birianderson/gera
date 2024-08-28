<template>
    <div>
        <input type="file" @change="uploadFile" />
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const file = ref(null)

const uploadFile = async (event) => {
    file.value = event.target.files[0]

    const formData = new FormData()
    formData.append('file', file.value)

    try {
        const response = await axios.post('/imovel/upload-excel', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        console.log('Upload successful', response.data)
    } catch (error) {
        console.error('Error uploading file', error)
    }
}
</script>
