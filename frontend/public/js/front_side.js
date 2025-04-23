// 切換側邊欄的功能
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const logo = document.getElementById('logo-text');
  const links = document.querySelectorAll('.link-text');
  const main = document.querySelector('.main');
  const userBlock = document.querySelector('.user-block'); // 選取 .user-block 元素

  const isCollapsed = sidebar.classList.contains('collapsed');
  sidebar.classList.toggle('collapsed');
  main.classList.toggle('expanded');

  logo.textContent = isCollapsed ? 'Muse' : 'MS';

  if (!isCollapsed) {
    logo.classList.add('animate');
    setTimeout(() => logo.classList.remove('animate'), 400);
  }

  links.forEach(link => {
    link.style.display = isCollapsed ? 'inline' : 'none';
  });

  // 替換 SVG 為圖片 (展開時)
  const userIconSpan = document.querySelector('a[href="user.html"] span.icon-holder');
  if (isCollapsed) {
    userIconSpan.innerHTML = `
      <img src="img/avatar.png" width="100" height="100" style="border-radius: 50%;" alt="avatar">
    `;
    userBlock.style.flexDirection = 'column'; // 展開時設置 flex-direction: column
  } else {
    userIconSpan.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
      </svg>
    `;
    userBlock.style.flexDirection = 'row'; // 收起時設置 flex-direction: row
  }

  // 推擠主頁面
  if (isCollapsed) {
    main.style.marginLeft = '150px';
  } else {
    main.style.marginLeft = '60px';
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const sidebarHTML = `
    <div class="sidebar collapsed" id="sidebar">
      <h2 id="logo-text">MS</h2>
      <style>
        .user-block {
          display: flex;
          align-items: center;
          padding: 15px 20px;
          color: #ccc;
          font-size: 20px;
          text-decoration: none;
          transition: 0.3s;
          white-space: nowrap;
        }
      </style>
      <a href="user.html" class="user-block">
        <span class="icon-holder">
          <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
          </svg>
        </span>
        <span class="link-text">用戶</span>
          <a href="index.html">
        <span>
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
          </svg>
        </span>
        <span class="link-text">首頁</span>
      </a>
      </a>
            <a href="ranking.html">
        <span>
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-bar-chart-fill" viewBox="0 0 16 16" transform="scale(-1, 1)">
            <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
          </svg>
        </span>
        <span class="link-text">排行</span>
      </a>
            <a href="Playlist.html">
        <span>
<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-collection-fill" viewBox="0 0 16 16">
  <path d="M0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6zM2 3a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 0-1h-11A.5.5 0 0 0 2 3m2-2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7A.5.5 0 0 0 4 1"/>
</svg>
        </span>
        <span class="link-text">歌單</span>
      </a>
    </div>
  `;

  document.getElementById('sidebar-container').innerHTML = sidebarHTML;
});