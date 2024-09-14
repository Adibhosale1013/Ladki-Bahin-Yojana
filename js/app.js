// Js For First splash screen while entering website : 

let intro =document.querySelector('.intro');
let logo = document.querySelector('.logo-header');
let logospan = document.querySelectorAll('.logo');

window.addEventListener('DOMContentLoaded',()=>{
    setTimeout(()=>{
        logospan.forEach((span, idx)=>{
            setTimeout(() =>{
               span.classList.add('active'); 
            }, (idx +1)*400)
        });

    setTimeout(()=>{
        logospan.forEach((span, idx)=>{
            setTimeout(() =>{
               span.classList.remove('active'); 
               span.classList.add('fade'); 
            }, (idx +1)*50)
        });
    },1000);

    setTimeout(()=>{
        intro.style.top= '-100vh';
    },1500)
  })
})


// slider for hero back
const slider = document.querySelector('.hero-slider');
const slides = document.querySelectorAll('.hero-slider img');
let slideIndex = 0;

function showSlide(n) {
  slides[slideIndex].style.display = 'none';
  slideIndex = (slideIndex + n + slides.length) % slides.length;
  slides[slideIndex].style.display = 'block';
}

showSlide(slideIndex);

setInterval(() => {
  showSlide(1);
}, 2000); // Change the interval time as needed



// Queries criteria solution section js : 

// Get all criteria divs and solution div
const criteriaDivs = document.querySelectorAll('.criteria .eligibility');
const solutionDiv = document.querySelector('.solution');

// Add a click event listener to each criteria div
criteriaDivs.forEach(criteriaDiv => {
  criteriaDiv.addEventListener('click', () => {
    // Get the heading text of the clicked criteria div
    const headingText = criteriaDiv.querySelector('h3').textContent;

    // Update the solution div content based on the heading
    switch (headingText) {
      case 'पात्रता':
        solutionDiv.innerHTML = '<ul><li>महाराष्ट्र राज्याचे रहिवाशी असणे आवश्यक.</li><li>राज्यातील विवाहीत, विधवा, घटस्फोटीत, परित्यक्ता आणि निराधार महिला तसेच कुटुंबातील केवळ एक अविवाहित महिला.</li><li>किमान वयाची २१ वर्षे पूर्ण व कमाल वयाची ६५ वर्ष पूर्ण होईपर्यंत.</li><li>लाभार्थ्याचे स्वतःचे आधार लिंक असलेले बँक खाते असावे.</li><li>लाभार्थी कुटुंबाचे वार्षिक उत्पन्न रु. २.५० लाखापेक्षा जास्त नसावे.</li></ul>';
        break;
      case 'अपात्रता':
        solutionDiv.innerHTML = '<ul><li>ज्यांच्या कुटुंबाचे एकत्रित वार्षिक उत्पन्न रु.२.५० लाख रुपयापेक्षा अधिक आहे.</li><li>ज्यांच्या कुटुंबातील सदस्य आयकरदाता आहे.</li><li>ज्यांच्या कुटुंबातील सदस्य नियमित / कायम कर्मचारी म्हणून सरकारी विभाग / उपक्रम/मंडळ / भारत सरकार किंवा राज्य सरकारच्या स्थानिक संस्थेमध्ये कार्यरत आहेत किंवा सेवानिवृत्तीनंतर निवृत्तीवेतन घेत आहेत. तथापि, रु. २.५० लाखा पर्यंत उत्पन्न असलेले बाह्य यंत्रणाद्वारे कार्यरत असलेले कर्मचारी, स्वयंसेवी कामगार आणि कंत्राटी कर्मचारी पात्र ठरतील.</li><li>सदर लाभार्थी महिला शासनाच्या इतर विभागा मार्फत राबविण्यात येणाऱ्या आर्थिक योजनेद्वारे दरमहा रु. १५००/- किंवा त्यापेक्षा जास्त रकमेचा लाभ घेत असेल.</li><li>ज्यांच्या कुटुंबातील सदस्य विद्यमान किंवा माजी खासदार / आमदार आहे.</li><li>ज्यांच्या कुटुंबातील सदस्य भारत सरकार किंवा राज्य सरकारच्या बोर्ड/कॉर्पोरेशन / उपक्रमाचे अध्यक्ष/उपाध्यक्ष/संचालक/सदस्य आहेत.</li><li>ज्यांच्याकडे चारचाकी वाहन (ट्रॅक्टर वगळून) त्यांच्या कुटुंबातील सदस्यांच्या नावावर नोंदणीकृत आहे</li></ul>';
        break;
      case 'अर्जप्रक्रिया':
        solutionDiv.innerHTML = '<ul><li>ज्या महिलेस ऑनलाइन अर्ज करता येत नसेल, त्यांना अंगणवाडी सेविका/पर्यवेक्षिका/मुख्यसेविका/सेतु सुविधा केंद्र/ग्रामसेवक/समुह संसाधन व्यक्ती (CRP) / आशा सेविका/वार्ड अधिकारी / CMM (सिटी मिशन मॅनेजर) / मनपा बालवाडी सेविका / मदत कक्ष प्रमुख / आपले सरकार सेवा केंद्र यांचेकडे ऑनलाइन / ऑफलाइन अर्ज भरण्याची सुविधा उपलब्ध असेल. या अर्जासाठी कोणत्याही प्रकारचे शुल्क आकारले जाणार नाही.</li><li>अर्जदाराचे नाव, जन्मदिनांक, पत्ता याबाबतची माहिती आधारकार्ड प्रमाणे अचूक भरण्यात यावी. बँकेचा तपशील व मोबाईल नंबर अचूक भरावा.</li></ul>';
        break;
      default:
        solutionDiv.innerHTML = '<ul><li>महाराष्ट्र राज्याचे रहिवाशी असणे आवश्यक.</li><li>राज्यातील विवाहीत, विधवा, घटस्फोटीत, परित्यक्ता आणि निराधार महिला तसेच कुटुंबातील केवळ एक अविवाहित महिला.</li><li>किमान वयाची २१ वर्षे पूर्ण व कमाल वयाची ६५ वर्ष पूर्ण होईपर्यंत.</li><li>लाभार्थ्याचे स्वतःचे आधार लिंक असलेले बँक खाते असावे.</li><li>लाभार्थी कुटुंबाचे वार्षिक उत्पन्न रु. २.५० लाखापेक्षा जास्त नसावे.</li></ul>';
    }
  });
});


// Night Mode Js : 
// Theme Toggle Functionality// Theme Toggle Functionality
document.addEventListener("DOMContentLoaded", () => {
  const themeToggle = document.getElementById("themeToggle");
  const body = document.body;

  if (themeToggle) { // Check if the themeToggle element exists
    // Check if dark mode is enabled in local storage
    if (localStorage.getItem("theme") === "dark") {
      body.classList.add("dark-mode");
      themeToggle.classList.replace("bi-moon-stars-fill", "bi-brightness-high-fill");
    }

    // Toggle dark mode and icon on click
    themeToggle.addEventListener("click", () => {
      body.classList.toggle("dark-mode");

      if (body.classList.contains("dark-mode")) {
        themeToggle.classList.replace("bi-moon-stars-fill", "bi-brightness-high-fill");
        localStorage.setItem("theme", "dark");
      } else {
        themeToggle.classList.replace("bi-brightness-high-fill", "bi-moon-stars-fill");
        localStorage.setItem("theme", "light");
      }
    });
  }
});

// Navbar Toggle for Small Screens
document.addEventListener("DOMContentLoaded", function() {
  const menuBtns = document.querySelectorAll(".menu-btn");  // All buttons
  const navs = document.querySelectorAll(".nav");            // All navs

  if (menuBtns.length > 0 && navs.length > 0) {
    menuBtns.forEach((menuBtn, index) => {
      const nav = navs[index]; // Match each button with its nav

      menuBtn.addEventListener("click", function() {
        if (nav) {
          nav.classList.toggle("show");
        }
      });
    });
  }
});
