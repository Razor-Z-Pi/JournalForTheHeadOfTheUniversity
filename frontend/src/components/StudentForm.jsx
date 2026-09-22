import { ref } from 'vue';
import axios from 'axios';

export default {
  setup() {
    const fullName = ref('');
    
    const addStudent = async () => {
      if (!fullName.value) return;
      await axios.post('http://localhost:8000/api/students', { full_name: fullName.value });
      fullName.value = '';
      // Здесь можно вызвать emit для обновления списка
    };

    return () => (
      <div class="input-group mb-3">
        <input 
          type="text" 
          class="form-control" 
          placeholder="ФИО студента" 
          v-model={fullName.value}
        />
        <button class="btn btn-primary" onClick={addStudent}>Добавить</button>
      </div>
    );
  }
};