<template>
  <div class="container mt-4">
    <h2>Управление дисциплинами</h2>

    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Добавить дисциплину</h5>
        <div class="row g-2">
          <div class="col-md-5">
            <input type="text" class="form-control" placeholder="Название предмета" v-model="newDiscipline.name">
          </div>
          <div class="col-md-5">
            <input type="text" class="form-control" placeholder="Преподаватель" v-model="newDiscipline.teacher_name">
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary w-100" @click="addDiscipline" :disabled="!newDiscipline.name">
              Добавить
            </button>
          </div>
        </div>
      </div>
    </div>

    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Название</th>
          <th>Преподаватель</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(d, index) in disciplines" :key="d.id">
          <td>{{ index + 1 }}</td>
          <td>{{ d.name }}</td>
          <td>{{ d.teacher_name || '—' }}</td>
          <td>
            <button class="btn btn-sm btn-danger" @click="deleteDiscipline(d.id)">Удалить</button>
          </td>
        </tr>
        <tr v-if="disciplines.length === 0">
          <td colspan="4" class="text-center text-muted">Дисциплин пока нет</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

const disciplines = ref([])
const newDiscipline = reactive({ name: '', teacher_name: '' })

const loadDisciplines = async () => {
  const res = await axios.get('/disciplines')
  disciplines.value = res.data
}

const addDiscipline = async () => {
  if (!newDiscipline.name.trim()) return
  await axios.post('/disciplines', { ...newDiscipline })
  newDiscipline.name = ''
  newDiscipline.teacher_name = ''
  await loadDisciplines()
}

const deleteDiscipline = async (id) => {
  if (!confirm('Удалить дисциплину? Все оценки по ней тоже удалятся.')) return
  await axios.delete(`/disciplines/${id}`)
  await loadDisciplines()
}

onMounted(loadDisciplines)
</script>