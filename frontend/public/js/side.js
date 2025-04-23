// 切換側邊欄的功能
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const logo = document.getElementById('logo-text');
    const links = document.querySelectorAll('.link-text');
    const main = document.querySelector('.main');
    
    const isCollapsed = sidebar.classList.contains('collapsed');
    sidebar.classList.toggle('collapsed');
    main.classList.toggle('expanded'); // 新增此行

    logo.textContent = isCollapsed ? 'Muse' : 'MS';

    if (!isCollapsed) {
      logo.classList.add('animate');
      setTimeout(() => logo.classList.remove('animate'), 400);
    }

    links.forEach(link => {
      link.style.display = isCollapsed ? 'inline' : 'none';
    });

    // 推擠主頁面
    if (isCollapsed) {
      main.style.marginLeft = '150px'; // 展開側邊欄時，主頁面推擠
    } else {
      main.style.marginLeft = '60px'; // 收起側邊欄時，主頁面恢復


    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    // 定義側邊欄的 HTML
    const sidebarHTML = `
      <div class="sidebar collapsed" id="sidebar">
        <h2 id="logo-text">MS</h2>
        <a href="Backstage_js.html"><span><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
          <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
        </svg></span><span class="link-text">主頁</span></a>
        <a href="Backmusic.html"><span><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-headphones" viewBox="0 0 16 16">
          <path d="M8 3a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V8a6 6 0 1 1 12 0v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1V8a5 5 0 0 0-5-5"/>
        </svg></span><span class="link-text">歌曲</span></a>
        <a href="BackPeople.html"><span><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
          <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
        </svg></span><span class="link-text">用戶</span></a>
        <a href="BackSet.html"><span><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
          <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1-.872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
        </svg></span><span class="link-text">設定</span></a>
      </div>
    `;
    
    // 插入側邊欄到頁面中
    document.getElementById('sidebar-container').innerHTML = sidebarHTML;
  });