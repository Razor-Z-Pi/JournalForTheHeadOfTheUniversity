<template>
  <div class="container-fluid px-3 px-md-4">
    <div class="journal-header d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
      <h2 class="mb-0">
        <i class="bi bi-table text-primary me-2"></i>Журнал группы
      </h2>
      <select class="form-select discipline-select" v-model="selectedDiscipline" @change="loadJournal">
        <option disabled value="">Выберите дисциплину</option>
        <option v-for="d in disciplines" :key="d.id" :value="d.id">
          {{ d.name }}
        </option>
      </select>
    </div>

    <transition name="fade-slide">
      <div v-if="selectedDiscipline" class="journal-card">
        <div class="table-responsive journal-wrapper">
          <table class="table table-bordered table-sm text-center align-middle mb-0 journal-table">
            <thead>
              <tr>
                <th class="sticky-col sticky-top corner-cell" style="min-width: 200px;">
                  ФИО Студента
                </th>
                <th v-for="week in 18" :key="week" class="sticky-top week-header" style="min-width: 64px;">
                  Нед. {{ week }}
                </th>
                <th class="sticky-top" style="min-width: 140px;">Примечание</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(student, idx) in students"
                :key="student.id"
                class="student-row"
                :style="{ animationDelay: (idx * 40) + 'ms' }"
              >
                <td class="sticky-col text-start student-name">
                  <i class="bi bi-person-circle text-muted me-1"></i>
                  {{ student.full_name }}
                </td>

                <td v-for="week in 18" :key="week" class="p-1 cell">
                  <div class="d-flex flex-column gap-1">
                    <select
                      class="form-select form-select-sm text-center grade-select"
                      v-model="getEntry(student.id, week).grade"
                      @change="saveCell(student.id, week)"
                      :class="gradeClass(getEntry(student.id, week).grade)"
                    >
                      <option value="">—</option>
                      <option value="5">5</option>
                      <option value="4">4</option>
                      <option value="3">3</option>
                      <option value="2">2</option>
                      <option value="н">н</option>
                      <option value="б">б</option>
                      <option value="у">у</option>
                    </select>
                    <input
                      type="number"
                      class="form-control form-control-sm text-center miss-input"
                      v-model.number="getEntry(student.id, week).missed_hours"
                      @change="saveCell(student.id, week)"
                      placeholder="0"
                      min="0"
                      max="20"
                    >
                  </div>
                </td>

                <td class="p-1">
                  <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="getEntry(student.id, 1).note"
                    @change="saveCell(student.id, 1)"
                    placeholder="..."
                  >
                </td>
              </tr>

              <tr v-if="students.length === 0">
                <td :colspan="20" class="text-center text-muted py-5 empty-cell">
                  <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                  В группе нет студентов. Добавьте их во вкладке "Студенты".
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </transition>

    <transition name="fade-slide">
      <div v-if="!selectedDiscipline" class="alert alert-info d-flex align-items-center gap-2 mt-3">
        <i class="bi bi-info-circle fs-4"></i>
        <span>Выберите дисциплину для начала работы</span>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import axios from 'axios'

const disciplines = ref([])
const selectedDiscipline = ref('')
const students = ref([])
const entriesMap = reactive({})

onMounted(async () => {
  const res = await axios.get('/disciplines')
  disciplines.value = res.data
})

const loadJournal = async () => {
  const res = await axios.get(`/journal/${selectedDiscipline.value}`)
  students.value = res.data.students

  Object.keys(entriesMap).forEach(key => delete entriesMap[key])

  res.data.entries.forEach(entry => {
    const key = `${entry.student_id}_${entry.week_number}`
    entriesMap[key] = {
      grade: entry.grade || '',
      missed_hours: entry.missed_hours || 0,
      note: entry.note || ''
    }
  })
}

const getEntry = (studentId, week) => {
  const key = `${studentId}_${week}`
  if (!entriesMap[key]) {
    entriesMap[key] = { grade: '', missed_hours: 0, note: '' }
  }
  return entriesMap[key]
}

const saveCell = async (studentId, week) => {
  const data = getEntry(studentId, week)
  const inputEl = document.activeElement
  try {
    await axios.post('/journal/update', {
      student_id: studentId,
      discipline_id: selectedDiscipline.value,
      week_number: week,
      grade: data.grade,
      missed_hours: data.missed_hours,
      note: data.note
    })
    if (inputEl) {
      inputEl.classList.add('saved-flash')
      setTimeout(() => inputEl.classList.remove('saved-flash'), 600)
    }
  } catch (e) {
    console.error('Ошибка сохранения:', e.response?.data || e.message)
  }
}

const gradeClass = (grade) => {
  if (grade === '5') return 'grade-5'
  if (grade === '4') return 'grade-4'
  if (grade === '3') return 'grade-3'
  if (grade === '2') return 'grade-2'
  if (grade === 'н') return 'grade-n'
  if (grade === 'б') return 'grade-b'
  if (grade === 'у') return 'grade-u'
  return ''
}
</script>

<style scoped>
.journal-header h2 {
  font-size: 1.5rem;
}

.discipline-select {
  max-width: 320px;
  border-radius: 10px;
  border: 1px solid var(--app-border);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.journal-card {
  background: #fff;
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid var(--app-border);
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
}

.journal-wrapper {
  max-height: 78vh;
  overflow: auto;
  position: relative;
}

.journal-table {
  margin: 0;
}

.journal-table thead th {
  background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
  color: #fff;
  font-weight: 600;
  font-size: 0.8rem;
  padding: 0.65rem 0.4rem;
  border-color: #374151;
  z-index: 2;
}

.sticky-top {
  position: sticky;
  top: 0;
  z-index: 3;
}

.corner-cell {
  z-index: 5 !important;
  background: linear-gradient(180deg, #1f2937 0%, #111827 100%) !important;
}

.sticky-col {
  position: sticky;
  left: 0;
  background: #fafbfc;
  border-right: 2px solid #e5e7eb !important;
  z-index: 1;
}

.student-row {
  animation: rowSlideIn 0.35s ease backwards;
}

@keyframes rowSlideIn {
  from {
    opacity: 0;
    transform: translateX(-8px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.student-row:hover .sticky-col {
  background: #eef2ff;
}

.student-row:hover td {
  background-color: #f8faff;
}

.student-name {
  font-weight: 600;
  font-size: 0.9rem;
  color: #111827;
  transition: background 0.2s ease;
}

.cell {
  background: #fff;
  transition: background 0.2s ease;
}

.grade-select {
  font-weight: 700;
  padding: 2px 4px;
  border-radius: 6px;
  font-size: 0.8rem;
  transition: all 0.2s ease;
}

.grade-5 { background: #dcfce7; color: #166534; border-color: #86efac; }
.grade-4 { background: #dbeafe; color: #1e40af; border-color: #93c5fd; }
.grade-3 { background: #fef3c7; color: #92400e; border-color: #fcd34d; }
.grade-2 { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
.grade-n { background: #fecaca; color: #7f1d1d; border-color: #f87171; }
.grade-b { background: #e9d5ff; color: #6b21a8; border-color: #c084fc; }
.grade-u { background: #cffafe; color: #155e75; border-color: #67e8f9; }

.miss-input {
  font-size: 0.75rem;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.miss-input:focus,
.grade-select:focus {
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
  border-color: var(--app-primary);
}

/* Анимация успешного сохранения */
:deep(.saved-flash) {
  animation: savedPulse 0.6s ease;
}

@keyframes savedPulse {
  0%   { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
  70%  { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
  100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}

.empty-cell {
  font-style: italic;
}

/* Мобильная адаптация */
@media (max-width: 768px) {
  .journal-header h2 {
    font-size: 1.25rem;
  }
  .discipline-select {
    max-width: 100%;
  }
  .sticky-col {
    min-width: 160px !important;
    font-size: 0.8rem;
  }
  .journal-wrapper {
    max-height: 70vh;
  }
}
</style>