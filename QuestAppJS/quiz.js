const questions = [
        
    { question: 'Güneş sistemindeki en büyük gezegen hangisidir?', id: 1, options: ['Mars', 'Jüpiter', 'Venüs', 'Satürn'], correct: 1 },
    { question: 'Telefonu icat eden kimdir?', id: 2, options: ['İbrahim Müteferrika', 'Nicola Tesla', 'Einstein', 'Thomas Edison'], correct: 3 },
    { question: 'Dünyadaki en uzun nehir hangisidir?', id: 3, options: ['Amazon', 'Nil', 'Yangtze', 'Mississippi'], correct: 1 },
    { question: 'Dünyanın en yüksek dağı hangisidir?', id: 4, options: ['Kangchenjunga', 'Everest', 'Makalu', 'Lhotse'], correct: 1 },
    { question: 'Hangi elementin sembolü Au’dur?', id: 5, options: ['Gümüş', 'Altın', 'Platin', 'Demir'], correct: 1 },
    { question: 'Hangi gezegen “Kırmızı Gezegen” olarak bilinir?', id: 6, options: ['Mars', 'Venüs', 'Satürn', 'Jüpiter'], correct: 0 },
    { question: 'Bir kilogram kaç gramdır?', id: 7, options: ['100', '500', '1000', '1500'], correct: 2 },
    { question: 'En büyük okyanus hangisidir?', id: 8, options: ['Atlantik Okyanusu', 'Hint Okyanusu', 'Pasifik Okyanusu', 'Arktik Okyanusu'], correct: 2 },
    { question: 'Hangi elementin sembolü H’dir?', id: 9, options: ['Helyum', 'Hidrojen', 'Hafniyum', 'Herkül'], correct: 1 },
    { question: 'Dünyanın en büyük çölü hangisidir?', id: 10, options: ['Sahra', 'Gobi', 'Antarktika', 'Karakum'], correct: 2 },
    { question: 'Türkiyenin en büyük dağı hangisidir?', id: 11, options: ['Everest', 'Ağrı Dağı', 'Nemrut Dağı', 'Toros Dağları'], correct: 1 },
    { question: 'Kötü ve rahatsız edici sesi olan insanlar hangi hayvana benzetilir?', id: 12, options: ['Kedi', 'Bülbül', 'Tilki', 'Karga'], correct: 3 },
    { question: 'Dünyada en çok konuşulan dil hangisidir?', id: 13, options: ['İngilizce', 'Çince', 'İspanyolca', 'Arapça'], correct: 1 },
    { question: 'Hangisi Samanyolu Galaksisinin en küçük gezegenidir?', id: 14, options: ['Venüs', 'Merkür', 'Mars', 'Neptün'], correct: 1 },
    { question: 'Hangi gezegen halkaları ile ünlüdür?', id: 15, options: ['Jüpiter', 'Uranüs', 'Satürn', 'Neptün'], correct: 2 },
    { question: 'Hangi organ vücudumuzda en büyük yüzey alanına sahiptir?', id: 16, options: ['Karaciğer', 'Akciğer', 'Deri', 'Mide'], correct: 2 },
    { question: 'Akdenizi Kızıldenize bağlayan kanal hangisidir?', id: 17, options: ['Süveyş', 'Panama', 'Cebelitarık', 'Çanakkale'], correct: 0 },
    { question: 'Sıfırı bulan bilim adamı kimdir?', id: 18, options: ['Ali Kuşçu', 'Harezmi', 'Arşimet', 'İbn-i Sina'], correct: 1 },
    { question: 'Hangi ülke en büyük yüzölçümüne sahiptir?', id: 19, options: ['Kanada', 'Çin', 'Rusya', 'Amerika Birleşik Devletleri'], correct: 2 },
    { question: 'Buz Devri isimli animasyon filmde Diego isimli karakteri seslendiren ünlümüz kimdir?', id: 20, options: ['Tuncel Kurtiz', 'Engin Günaydın', 'Okan Bayülgen', 'Haluk Bilginer'], correct: 3 },
    { question: '2006 yılında gezegenlik rütbesi sökülerek Cüce Gezegen ismini alan gök cismi hangisidir?', id: 21, options: ['Uranüs', 'Halley', 'Neptün', 'Pluton'], correct: 0 },
    { question: 'Dünyada en çok bulunan element hangisidir?', id: 22, options: ['Oksijen', 'Hidrojen', 'Karbon', 'Azot'], correct: 0 },
    { question: 'Savaş ve Barış adlı eserin yazarı kimdir?', id: 23, options: ['Dostoyevski', 'Jack London', 'Anton Çehov', 'Tolstoy'], correct: 3 },
    { question: 'Alp Dağları hangi ülkededir?', id: 24, options: ['İsveç', 'Danimarka', 'İsviçre', 'Japonya'], correct: 2 },
    { question: 'Harry Potter serisini yazan ünlü romancı kimdir?', id: 25, options: ['J. K. Rowling', 'Suzanne Colins', 'Stephen King', 'J. S. Lewis'], correct: 0 },
    { question: 'Hangisi Dünya’nın en büyük denizidir?', id: 26, options: ['Karadeniz', 'Akdeniz', 'Bering Denizi', 'Hindistan Denizi'], correct: 1 },
    { question: 'Starry Night tablosu hangi ünlü sanatçıya aittir?', id: 27, options: ['Leonardo Da Vinci', 'Vincent Van Gogh', 'Pablo Picasso', 'Claude Monet'], correct: 1 },
    { question: 'En yoğun sıvı nedir?', id: 28, options: ['Su', 'Civa', 'Alkol', 'Gliserin'], correct: 1 },
    { question: 'Hangi elementin kimyasal sembolü “Hg”dir?', id: 29, options: ['Altın', 'Cıva', 'Helyum', 'Gümüş'], correct: 1 },
    { question: 'Hangi ülkenin başkenti Nairobidir?', id: 30, options: ['Uganda', 'Kenya', 'Guatemala', 'Uruguay'], correct: 1 },
    { question: 'Yüzüklerin Efendisi serisinin yazarı kimdir?', id: 31, options: ['George R. R. Martin', 'J. K. Rowling', 'C. S. Lewis', 'J. R. R. Tolkien'], correct: 3 },
    { question: 'Sembolü Na olan elemenet hangisidir?', id: 32, options: ['Nikel', 'Namibyum', 'Potasyum', 'Sodyum'], correct: 3 },
    { question: 'Ünlü eser Don Kişotun yazarı hangisidir?', id: 33, options: ['Franciso de Quevedo', 'Gabriel Garcia Marquez', 'Miguel de Cervantes', 'Benito Perez Galdos'], correct: 2 },
    { question: 'Hangi ünlü müzik grubu Bohemian Rapsody adlı parçayı seslendirmiştir?', id: 34, options: ['Queen', 'Led Zeppelin', 'The Beatles', 'Pink Floyd'], correct: 0 },
    { question: 'Hangisi bir tatlı su gölüdür?', id: 35, options: ['Büyük Tuz Gölü', 'Baykal Gölü', 'Karakum Gölü', 'Tuz Gölü'], correct: 1 },
    { question: 'Hangi ünlü şehir, “Sagrada Família” bazilikası ile tanınır?', id: 36, options: ['Barselona', 'Paris', 'Madrid', 'Roma'], correct: 0 },
    { question: 'Hangi ünlü sanatçı “David” adlı heykeli ile tanınır?', id: 37, options: ['Donatello', 'Michelangelo', 'Raphael', 'Leonardo Da Vinci'], correct: 1 },
    { question: 'Hangi ünlü sanatçı “Son Akşam Yemeği” adlı tablosunun yaratıcısıdır?', id: 38, options: ['Vincent Van Gogh', 'Leonardo Da Vinci', 'Michelangelo', 'Raphael'], correct: 1 },
    { question: 'Karamazov Kardeşler adlı yapıtın yazarı kimdir?', id: 39, options: ['Anton Çehov', 'Aleksandr Puşkin', 'Lev Tolstoy', 'Fyodor Dostoyevski'], correct: 3 },
    { question: 'Hangi gezegenin yüzeyi en sıcak olanıdır?', id: 40, options: ['Venüs', 'Mars', 'Jüpiter', 'Uranüs'], correct: 0 },
    { question: 'Hangi ünlü şehir, “Louvre” müzesi ile tanınır?', id: 41, options: ['Paris', 'Londra', 'St. Etienne', 'Madrid'], correct: 0 },
    { question: 'Hangi ünlü film yapımcısı “Inception” adlı filmi ile tanınır?', id: 42, options: ['Steven Spielberg', 'Christopher Nolan', 'Quentin Tarantino', 'Martin Scorsese'], correct: 1 },
    { question: 'Hangi ünlü şehir “Big Ben” adlı saati ile tanınır?', id: 43, options: ['Paris', 'Londra', 'Roma', 'Berlin'], correct: 1 },
    { question: 'Türkiyenin en uzun nehri hangisidir?', id: 44, options: ['Yeşilırmak', 'Dicle', 'Kızılırmak', 'Fırat'], correct: 2 },
    { question: 'Hangisi en eski gezegendir?', id: 45, options: ['Dünya', 'Mars', 'Venüs', 'Jüpiter'], correct: 3 },
    { question: 'Hangi gezegen en fazla su buharına sahiptir?', id: 46, options: ['Venüs', 'Neptün', 'Uranüs', 'Mars'], correct: 1 },
    { question: 'Hangi ünlü Türk yazar, “İnce Memed” adlı romanı ile tanınır?', id: 47, options: ['Yaşar Kemal', 'Orhan Kemal', 'Sabahattin Ali', 'Refik Halit Karay'], correct: 0 },
    { question: 'En büyük yıldız hangisidir?', id: 48, options: ['Sirius', 'Betelgeuse', 'Antares', 'Vega'], correct: 1 },
    { question: 'Türkiyenin hangi ilinde, ünlü “Göbekli Tepe” arkeolojik alanı bulunmaktadır?', id: 49, options: ['Gaziantep', 'Şanlıurfa', 'Diyarbakır', 'Mardin'], correct: 1 },
    { question: 'Türkiyenin en uzun köprüsü hangisidir?', id: 50, options: ['Osmangazi Köprüsü', 'Fatih Sultan Mehmet Köprüsü', '15 Temmuz Şehitler Köprüsü', 'Yavuz Sultan Selim Köprüsü'], correct: 0 }
];

let currentQuestionIndex = 0;
let score = 0;
let askedQuestions = [];

const startButton = document.getElementById("startQuizButton");
const questionContainer = document.getElementById("questionContainer");
const questionElement = document.getElementById("questionText");
const answersList = document.getElementById("answersList");
const feedbackElement = document.getElementById("feedback");
const feedbackText = document.getElementById("feedbackText");
const nextButton = document.getElementById("nextQuestionButton");

startButton.addEventListener("click", startQuiz);
nextButton.addEventListener("click", loadNextQuestion);

function startQuiz() {
    document.getElementById("welcomeScreen").style.display = "none";
    document.getElementById("quizScreen").style.display = "block";
    loadNextQuestion();
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

    const shuffledOptions = [...currentQuestion.options];
    shuffledOptions.sort(() => Math.random() - 0.5);

    shuffledOptions.forEach((choice, index) => {
        const button = document.createElement("button");
        button.innerText = choice;
        button.classList.add("choiceButton");
        button.addEventListener("click", () => selectAnswer(index, currentQuestion.correct, button, shuffledOptions));
        answersList.appendChild(button);
    });
}

function resetState() {
    feedbackElement.style.display = "none";
    nextButton.style.display = "none";
    while (answersList.firstChild) {
        answersList.removeChild(answersList.firstChild);
    }
}

function selectAnswer(selectedIndex, correctIndex, selectedButton, shuffledOptions) {
    const choiceButtons = Array.from(answersList.children);
    choiceButtons.forEach(button => button.disabled = true);

    const correctAnswerIndex = shuffledOptions.indexOf(questions[askedQuestions[askedQuestions.length - 1]].options[correctIndex]);

    if (selectedIndex === correctAnswerIndex) {
        selectedButton.classList.add("correct");
        feedbackText.innerText = "Doğru cevap!";
        score++;
    } else {
        selectedButton.classList.add("incorrect");
        choiceButtons[correctAnswerIndex].classList.add("correct");
        feedbackText.innerText = "Yanlış cevap!";
    }

    feedbackElement.style.display = "block";
    nextButton.style.display = "block";
}

function endQuiz() {
    document.getElementById("quizScreen").style.display = "none";
    document.getElementById("resultScreen").style.display = "block";
    document.getElementById("scoreText").innerText = `Yarışma bitti! Skorunuz: ${score}/10`;
}
