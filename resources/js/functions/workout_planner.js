
// This file contains the JavaScript functions for the workout planner page.

// This function is used to fetch exercises based on the selected muscle and type.
document.addEventListener('DOMContentLoaded', function() {
    const muscleSelect = document.getElementById('muscle');
    const typeSelect = document.getElementById('type');
    const exercisesSelect = document.getElementById('exercises');

    async function fetchExercises() {
        const muscle = muscleSelect.value; 
        const type = typeSelect.value;

        if (muscle && type) {
            try {
                const response = await axios.get('/api/fetch_exercises', {
                    params: { muscle, type }
                });

                exercisesSelect.disabled = false;
                exercisesSelect.innerHTML = '<option value="">Select Exercise</option>';

                response.data.forEach(exercise => {
                    const option = document.createElement('option');
                    option.value = exercise.name;
                    option.textContent = exercise.name;
                    exercisesSelect.appendChild(option);
                });

            } catch (error) {
                console.error(error);
                alert('Failed to fetch Exercises, Please try again later.');
            }
        } else {
            exercisesSelect.disabled = true;
        }
    }

    muscleSelect.addEventListener('change', fetchExercises);
    typeSelect.addEventListener('change', fetchExercises);
});