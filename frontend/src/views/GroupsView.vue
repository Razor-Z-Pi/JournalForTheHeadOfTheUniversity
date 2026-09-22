<template>
  <div class="container mt-4">
    <h2>Группы и подгруппы</h2>

    <!-- Добавление группы -->
    <div class="card mb-4">
      <div class="card-body">
        <h5>Добавить группу</h5>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Название группы (например, буква-цифра)"
                 v-model="newGroupName" @keyup.enter="addGroup">
          <button class="btn btn-primary" @click="addGroup" :disabled="!newGroupName">Добавить</button>
        </div>
      </div>
    </div>

    <!-- Список групп -->
    <div v-for="group in groups" :key="group.id" class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <strong>{{ group.name }}</strong>
        <div>
          <span class="badge bg-secondary me-2">{{ group.students_count }} студентов</span>
          <button class="btn btn-sm btn-danger" @click="deleteGroup(group.id)">Удалить</button>
        </div>
      </div>
      <div class="card-body">
        <!-- Подгруппы -->
        <h6>Подгруппы:</h6>
        <div class="d-flex flex-wrap gap-2 mb-3">
          <div v-for="sg in group.subgroups" :key="sg.id"
               class="badge bg-info text-dark d-flex align-items-center gap-2 p-2">
            {{ sg.name }}
            <button class="btn-close btn-close-white" style="font-size: 0.6rem;"
                    @click="deleteSubgroup(sg.id)"></button>
          </div>
          <span v-if="group.subgroups.length === 0" class="text-muted">нет подгрупп</span>
        </div>

        <!-- Форма добавления подгруппы -->
        <div class="input-group input-group-sm" style="max-width: 400px;">
          <input type="text" class="form-control" placeholder="Название подгруппы"
                 v-model="newSubgroupNames[group.id]" @keyup.enter="addSubgroup(group.id)">
          <button class="btn btn-outline-primary" @click="addSubgroup(group.id)">+ Подгруппа</button>
        </div>
      </div>
    </div>

    <div v-if="groups.length === 0" class="alert alert-info">
      Групп пока нет. Добавьте первую.
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

const groups = ref([])
const newGroupName = ref('')
const newSubgroupNames = reactive({})

const loadGroups = async () => {
  const res = await axios.get('/groups')
  groups.value = res.data
}

const addGroup = async () => {
  if (!newGroupName.value.trim()) return
  try {
    await axios.post('/groups', { name: newGroupName.value })
    newGroupName.value = ''
    await loadGroups()
  } catch (e) {
    alert('Ошибка: ' + JSON.stringify(e.response?.data || e.message))
  }
}

const deleteGroup = async (id) => {
  if (!confirm('Удалить группу? Студенты останутся, но потеряют привязку.')) return
  await axios.delete(`/groups/${id}`)
  await loadGroups()
}

const addSubgroup = async (groupId) => {
  const name = newSubgroupNames[groupId]
  if (!name || !name.trim()) return
  try {
    await axios.post('/subgroups', { group_id: groupId, name })
    newSubgroupNames[groupId] = ''
    await loadGroups()
  } catch (e) {
    alert('Ошибка: ' + JSON.stringify(e.response?.data || e.message))
  }
}

const deleteSubgroup = async (id) => {
  if (!confirm('Удалить подгруппу?')) return
  await axios.delete(`/subgroups/${id}`)
  await loadGroups()
}

onMounted(loadGroups)
</script>