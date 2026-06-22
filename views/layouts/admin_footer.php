  </div>

  <script>
    // Sidebar toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebarOverlay');
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
      } else {
        const collapsed = sidebar.style.transform === 'translateX(-100%)';
        sidebar.style.transform = collapsed ? '' : 'translateX(-100%)';
        document.querySelector('.main-wrap').style.marginLeft = collapsed ? 'var(--sidebar-w)' : '0';
        document.cookie = 'sidebar_collapsed=' + !collapsed + '; path=/; max-age=' + 365*24*60*60;
      }
    });
    document.getElementById('sidebarOverlay')?.addEventListener('click', () => {
      document.getElementById('sidebar').classList.remove('open');
      document.getElementById('sidebarOverlay').classList.remove('open');
    });

    // Dark mode toggle
    document.getElementById('darkToggleAdmin')?.addEventListener('click', () => {
      const isDark = document.documentElement.classList.toggle('dark');
      document.body.classList.toggle('dark', isDark);
      document.cookie = 'dark_mode=' + isDark + '; path=/; max-age=' + 365*24*60*60;
    });

    // Auto-dismiss flash
    setTimeout(() => {
      document.querySelector('.flash')?.remove();
    }, 5000);
  </script>
</body>
</html>
