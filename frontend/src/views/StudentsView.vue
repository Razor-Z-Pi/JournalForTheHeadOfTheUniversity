<template>
  <div class="container-fluid px-3 px-md-4">
    <h2 class="mb-4"><i class="bi bi-people-fill text-primary me-2"></i>Управление студентами</h2>

    <transition name="fade-slide">
      <div class="card form-card mb-4">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-person-plus me-2"></i>Добавить студента</h5>
          <div class="row g-2">
            <div class="col-md-5">
              <input type="text" class="form-control" placeholder="ФИО студента"
                     v-model="newStudent.full_name" @keyup.enter="addStudent">
            </div>
            <div class="col-md-3">
              <select class="form-select" v-model="newStudent.group_id" @change="onGroupChange">
                <option :value="null">Без группы</option>
                <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select" v-model="newStudent.subgroup_id" :disabled="!newStudent.group_id">
                <option :value="null">Без подгруппы</option>
                <option v-for="sg in availableSubgroups" :key="sg.id" :value="sg.id">{{ sg.name }}</option>
              </select>
            </div>
            <div class="col-md-1">
              <button class="btn btn-primary w-100" @click="addStudent" :disabled="!newStudent.full_name">
                <i class="bi bi-plus-lg"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <div class="card">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>ФИО</th>
              <th>Группа</th>
              <th>Подгруппа</th>
              <th>Статус</th>
              <th class="text-end">Действия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(student, index) in students" :key="student.id" class="student-row"
                :style="{ animationDelay: (index * 40) + 'ms' }">
              <td class="text-muted">{{ index + 1 }}</td>
              <td class="fw-semibold">{{ student.full_name }}</td>
              <td><span class="badge bg-light text-dark border">{{ student.group?.name || '—' }}</span></td>
              <td><span class="badge bg-light text-dark border">{{ student.subgroup?.name || '—' }}</span></td>
              <td>
                <span class="badge" :class="student.is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'">
                  <i class="bi" :class="student.is_active ? 'bi-check-circle' : 'bi-archive'"></i>
                  {{ student.is_active ? 'Активен' : 'В архиве' }}
                </span>
              </td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-danger" @click="deleteStudent(student.id)">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
            <tr v-if="students.length === 0">
              <td colspan="6" class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Студентов пока нет
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import axios from 'axios'

const students = ref([])
const groups = ref([])
const newStudent = reactive({ full_name: '', group_id: null, subgroup_id: null })

const availableSubgroups = computed(() => {
  if (!newStudent.group_id) return []
  const g = groups.value.find(g => g.id === newStudent.group_id)
  return g ? g.subgroups : []
})

const loadData = async () => {
  const [s, g] = await Promise.all([axios.get('/students'), axios.get('/groups')])
  students.value = s.data
  groups.value = g.data
}

const onGroupChange = () => { newStudent.subgroup_id = null }

const addStudent = async () => {
  try {
    await axios.post('/students', { ...newStudent })
    newStudent.full_name = ''
    newStudent.subgroup_id = null
    await loadData()
  } catch (e) {
    alert('Ошибка: ' + JSON.stringify(e.response?.data || e.message))
  }
}

const deleteStudent = async (id) => {
  if (!confirm('Удалить студента?')) return
  await axios.delete(`/students/${id}`)
  await loadData()
}

onMounted(loadData)
</script>

<style scoped>
.form-card {
  background: linear-gradient(180deg, #fff 0%, #fafbff 100%);
}

.card-title {
  font-weight: 600;
  color: var(--app-dark);
}

.table thead th {
  background: #f9fafb;
  color: #374151;
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--app-border);
  padding: 0.85rem 1rem;
}

.table tbody td {
  padding: 0.85rem 1rem;
  border-color: var(--app-border);
}

.student-row {
  animation: rowSlideIn 0.35s ease backwards;
}

@keyframes rowSlideIn {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}

.student-row:hover td {
  background: #f8faff;
}

.badge {
  font-weight: 500;
  padding: 0.4em 0.7em;
  border-radius: 6px;
}
</style>