let currentQuestionIndex = 0;
let score = 0;
let askedQuestions = [];
let questions = [];

const startButton = document.getElementById("startQuizButton");
const questionContainer = document.getElementById("questionContainer");
const questionElement = document.getElementById("questionText");
const answersList = document.getElementById("answersList");
const feedbackElement = document.getElementById("feedback");
const feedbackText = document.getElementById("feedbackText");
const nextButton = document.getElementById("nextQuestionButton");
feedbackText.style.color = "white";
feedbackText.style.fontSize = "20px";

startButton.addEventListener("click", startQuiz);
nextButton.addEventListener("click", loadNextQuestion);

function resetState() {
    feedbackElement.style.display = "none";
    nextButton.style.display = "none";
    while (answersList.firstChild) {
        answersList.removeChild(answersList.firstChild);
    }
}

function selectAnswer(selectedIndex, correctIndex, selectedButton, shuffledOptions) {
    const answerButtons = Array.from(answersList.children);
    answerButtons.forEach(button => button.disabled = true);

    const correctAnswerIndex = shuffledOptions.indexOf(questions[askedQuestions[askedQuestions.length - 1]][`option${correctIndex}`]);

    if (selectedIndex === correctAnswerIndex) {
        selectedButton.classList.add("correct");
        feedbackText.innerText = "Doğru cevap!";
        score++;
    } else {
        selectedButton.classList.add("incorrect");
        answerButtons[correctAnswerIndex].classList.add("correct");
        feedbackText.innerText = "Yanlış cevap!";
    }

    feedbackElement.style.display = "flex";
    feedbackElement.style.flexDirection = "column";
    feedbackElement.style.alignItems = "center";
    feedbackElement.style.justifyContent = "center";
    feedbackElement.style.maxWidth = "800px";
    nextButton.style.display = "block";
}

function endQuiz() {
    document.getElementById("quizScreen").style.display = "none";
    document.getElementById("resultScreen").style.display = "block";
    document.getElementById("scoreText").innerHTML = `Yarışma bitti! Skorunuz: ${score}/10`;

    const formData = new FormData();
    formData.append('correctAnswers', score); 
    formData.append('totalQuestions', 10); 

    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); 
    })
    .catch(error => console.error('Hata:', error));
}

function loadNextQuestion() {
    resetState();

    if (askedQuestions.length === 10) {
        endQuiz();
        return;
    }

    let randomIndex;
    do {
        randomIndex = Math.floor(Math.random() * questions.length);
    } while (askedQuestions.includes(randomIndex));

    askedQuestions.push(randomIndex);

    const currentQuestion = questions[randomIndex];
    questionElement.innerText = currentQuestion.question;

    const shuffledOptions = [currentQuestion.option1, currentQuestion.option2, currentQuestion.option3, currentQuestion.option4];
    shuffledOptions.sort(() => Math.random() - 0.5);

    shuffledOptions.forEach((choice, index) => {
        const button = document.createElement("button");
        button.innerText = choice;
        button.classList.add("choiceButton");
        button.addEventListener("click", () => selectAnswer(index, currentQuestion.correct_option, button, shuffledOptions));
        answersList.appendChild(button);
    });
}

function fetchQuestions() {
    fetch('get_questions.php')
        .then(response => response.json())
        .then(data => {
            questions = data; 
            loadNextQuestion();
        })
        .catch(error => console.error('Error fetching questions:', error));
}

function startQuiz() {
    document.getElementById("welcomeScreen").style.display = "none";
    document.getElementById("quizScreen").style.display = "flex";
    document.getElementById("quizScreen").style.flexDirection = "column";
    fetchQuestions();
}

