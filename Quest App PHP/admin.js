document.addEventListener('DOMContentLoaded', function() { 
    const listQuestionsBtn = document.getElementById('listQuestionsButton');
    const initialView = document.getElementById('welcome');
    const questionsList = document.getElementById('questionList');
    const searchInput = document.getElementById('search');
    const ul = questionsList.querySelector('ul');

    let questions = [];

    async function fetchQuestions() {
        try {
            const response = await fetch('fetch_questions.php');
            questions = await response.json();
            renderQuestions(questions);
        } catch (error) {
            console.error('Error fetching questions:', error);
        }
    }

    function renderQuestions(filteredQuestions) {
        ul.innerHTML = '';
        filteredQuestions.forEach(q => {
            const li = document.createElement('li');
            li.innerHTML = `
                ${q.question}
                <div class="buttonContainer">
                    <button class="editButton" data-id="${q.id}">Düzenle</button>
                    <button class="deleteButton" data-id="${q.id}">Sil</button>
                </div>
            `;
            ul.appendChild(li);
        });
        attachEventListeners();
    }

    function attachEventListeners() {
        document.querySelectorAll('.editButton').forEach(btn => {
            btn.addEventListener('click', handleEdit);
        });
        document.querySelectorAll('.deleteButton').forEach(btn => {
            btn.addEventListener('click', handleDelete);
        });
    }

    function filterQuestions() {
        const searchText = searchInput.value.toLowerCase();
        const filteredQuestions = questions.filter(q => q.question.toLowerCase().includes(searchText));
        renderQuestions(filteredQuestions);
    }

    function handleEdit(event) {
        const questionId = event.target.getAttribute('data-id');
        window.location.href = `edit_question.php?id=${questionId}`;
    }

    async function handleDelete(event) {
        const questionId = event.target.getAttribute('data-id');
        if (confirm('Bu soruyu silmek istediğinize emin misiniz?')) {
            try {
                const response = await fetch(`delete_question.php?id=${questionId}`, { method: 'DELETE' });
                if (response.ok) {
                    fetchQuestions(); 
                } else {
                    alert('Silme işlemi başarısız oldu.'); 
                }
            } catch (error) {
                console.error('Silme hatası:', error);
            }
        }
    }

    searchInput.addEventListener('input', filterQuestions);

    listQuestionsBtn.addEventListener('click', function() {
        initialView.style.display = 'none';
        questionsList.style.display = 'block';
        fetchQuestions(); 
    });
});
