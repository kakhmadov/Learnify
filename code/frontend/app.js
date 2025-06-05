// app.js

// Kleines Skript, um das User-Dropdown per Klick zu toggeln
document.addEventListener('DOMContentLoaded', () => {
  const userToggle = document.querySelector('.user-dropdown .dropdown-toggle');
  const userDropdown = document.querySelector('.user-dropdown');

  userToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    userDropdown.classList.toggle('active');
  });

  // Klick irgendwo außerhalb schließt das Dropdown
  document.addEventListener('click', (e) => {
    if (!userDropdown.contains(e.target)) {
      userDropdown.classList.remove('active');
    }
  });
});


document.addEventListener('DOMContentLoaded', () => {
  const subjectsDropdown = document.querySelector('.subjects-dropdown');
  const megaMenu         = document.querySelector('.mega-menu');

  if (!subjectsDropdown || !megaMenu) return;

  // Funktion: Menü einblenden
  const showMegaMenu = () => {
    megaMenu.style.display = 'flex';
  };

  // Funktion: Menü ausblenden
  const hideMegaMenu = () => {
    megaMenu.style.display = 'none';
  };

  // Wenn Maus über „Subjects“ fährt, Menü zeigen
  subjectsDropdown.addEventListener('mouseenter', () => {
    showMegaMenu();
  });

  // Wenn Maus den „Subjects“-Bereich verlässt,
  // aber noch nicht ins Mega-Menu hineinfährt, bleibt es kurz sichtbar.
  // Wir setzen hier einen kleinen Timeout, um das kleine Lücke-Problem zu überbrücken.
  subjectsDropdown.addEventListener('mouseleave', () => {
    setTimeout(() => {
      // Erst verstecken, wenn Maus weder über Subjects noch im MegaMenu ist
      if (
        !subjectsDropdown.matches(':hover') &&
        !megaMenu.matches(':hover')
      ) {
        hideMegaMenu();
      }
    }, 150); // 150 ms Puffer
  });

  // Wenn Maus in das MegaMenu hineinfährt, soll es sichtbar bleiben
  megaMenu.addEventListener('mouseenter', () => {
    showMegaMenu();
  });

  // Wenn Maus das MegaMenu verlässt, soll es erst verschwinden,
  // wenn Cursor auch nicht über „Subjects“ ist
  megaMenu.addEventListener('mouseleave', () => {
    setTimeout(() => {
      if (
        !subjectsDropdown.matches(':hover') &&
        !megaMenu.matches(':hover')
      ) {
        hideMegaMenu();
      }
    }, 150);
  });
});


function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('svg');
    answer.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
  }

  