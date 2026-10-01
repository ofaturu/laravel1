<script setup>
import { onMounted, ref } from 'vue';

const alatList = ref([]);
const loading = ref(false);
const form = ref({
    id: null,
    nama_alat: '',
    tahun: new Date().getFullYear(),
    merek: '',
    lokasi: '',
});
const isEditing = ref(false);

async function fetchAlat() {
    loading.value = true;
    try {
        const { data } = await axios.get('/alat');
        alatList.value = data;
    } catch (error) {
        console.error(error);
        alert('Gagal memuat data alat');
    } finally {
        loading.value = false;
    }
}

function resetForm() {
    form.value = {
        id: null,
        nama_alat: '',
        tahun: new Date().getFullYear(),
        merek: '',
        lokasi: '',
    };
    isEditing.value = false;
}

function editAlat(item) {
    form.value = { ...item };
    isEditing.value = true;
}

async function submitForm() {
    try {
        if (isEditing.value) {
            await axios.put(`/alat/${form.value.id}`, form.value);
        } else {
            await axios.post('/alat', form.value);
        }

        resetForm();
        await fetchAlat();
    } catch (error) {
        console.error(error);
        const messages = error.response?.data?.errors;

        if (messages) {
            alert(Object.values(messages).flat().join('\n'));
            return;
        }

        alert('Data gagal disimpan');
    }
}

async function deleteAlat(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus alat ini?')) {
        return;
    }

    try {
        await axios.delete(`/alat/${id}`);
        await fetchAlat();
    } catch (error) {
        console.error(error);
        alert('Gagal menghapus alat');
    }
}

onMounted(() => {
    fetchAlat();
});
</script>

<template>
    <div class="sneat-shell">
        <aside class="sneat-sidebar">
            <div class="sneat-brand">
                <span class="sneat-brand-mark"></span>
                <span>sneat</span>
            </div>

            <ul class="sneat-menu">
                <li>
                    <button class="active">
                        <span>★</span>
                        <span>Menu Alat</span>
                    </button>
                </li>
                <li>
                    <button>
                        <span>☆</span>
                        <span>Master Data</span>
                    </button>
                </li>
                <li>
                    <button>
                        <span>☆</span>
                        <span>Daftar</span>
                    </button>
                </li>
                <li>
                    <button>
                        <span>☆</span>
                        <span>Pengaturan</span>
                    </button>
                </li>
            </ul>
        </aside>

        <main class="sneat-main">
            <header class="sneat-topbar">
                <button class="sneat-toggle">‹</button>
                <div class="sneat-search">
                    <span>⌕</span>
                    <input type="text" value="" placeholder="Search" />
                </div>
                <button class="sneat-user">◔</button>
            </header>

            <div class="sneat-content">
                <div class="sneat-page-title">Layouts / <strong>Container</strong></div>

                <div class="sneat-form-card mb-4">
                    <form @submit.prevent="submitForm" class="row g-3 sneat-form">
                        <div class="col-md-6">
                            <label class="form-label">Nama Alat</label>
                            <input v-model="form.nama_alat" type="text" class="form-control" required />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tahun</label>
                            <input v-model.number="form.tahun" type="number" min="1900" max="2100" class="form-control" required />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Merek</label>
                            <input v-model="form.merek" type="text" class="form-control" required />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Lokasi</label>
                            <input v-model="form.lokasi" type="text" class="form-control" required />
                        </div>

                        <div class="col-12 sneat-action-row">
                            <button type="button" class="sneat-btn sneat-btn-secondary" @click="resetForm">
                                Batal
                            </button>
                            <button type="submit" class="sneat-btn sneat-btn-primary">
                                {{ isEditing ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="sneat-table-card">
                    <div class="table-responsive">
                        <table class="sneat-table">
                            <thead>
                                <tr>
                                    <th>Nama Alat</th>
                                    <th>Tahun</th>
                                    <th>Merek</th>
                                    <th>Lokasi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="loading">
                                    <td colspan="5" class="sneat-empty">Memuat data...</td>
                                </tr>
                                <tr v-else-if="alatList.length === 0">
                                    <td colspan="5" class="sneat-empty">Belum ada data alat</td>
                                </tr>
                                <tr v-for="item in alatList" :key="item.id">
                                    <td>{{ item.nama_alat }}</td>
                                    <td>{{ item.tahun }}</td>
                                    <td>{{ item.merek }}</td>
                                    <td>{{ item.lokasi }}</td>
                                    <td>
                                        <div class="sneat-table-actions">
                                            <button class="sneat-btn sneat-btn-warning btn-sm" @click="editAlat(item)">
                                                Edit
                                            </button>
                                            <button class="sneat-btn sneat-btn-danger btn-sm" @click="deleteAlat(item.id)">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
