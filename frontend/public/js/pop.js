// 開啟彈窗
function openModal() {
  document.getElementById('modal').style.display = 'flex';
}

// 關閉彈窗
function closeModal() {
  document.getElementById('modal').style.display = 'none';

  // 清空新密碼和確認密碼的輸入框
  
  const newPasswordInput = document.getElementById('new-password');
  const confirmPasswordInput = document.getElementById('confirm-password');
  const errorMessage = document.getElementById('error-message');

  if (newPasswordInput) {newPasswordInput.value = ''};
  if (confirmPasswordInput) {confirmPasswordInput.value = ''};

  // 隱藏錯誤訊息
  if (errorMessage) {errorMessage.style.display = 'none'};
}

// 驗證密碼是否一致
function validatePasswords() {
  const newPassword = document.getElementById('new-password').value;
  const confirmPassword = document.getElementById('confirm-password').value;
  const errorMessage = document.getElementById('error-message');

  if (newPassword !== confirmPassword) {
      errorMessage.style.display = 'block'; // 顯示錯誤訊息
      return false; // 阻止表單提交
  } else {
      errorMessage.style.display = 'none'; // 隱藏錯誤訊息
      return true; // 允許表單提交
  }
}