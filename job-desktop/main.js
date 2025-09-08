const { app, BrowserWindow, ipcMain, dialog } = require('electron');
const path = require('path');
const { autoUpdater } = require('electron-updater');

let win;

function createWindow() {
  win = new BrowserWindow({
    width: 800,
    height: 600,
    webPreferences: {
      preload: path.join(__dirname, 'renderer.js'),
      nodeIntegration: true,
      contextIsolation: false,
    }
  });

  win.loadFile('loginpage/index.html');

  // Explicitly set GitHub update feed (highly recommended)
  autoUpdater.setFeedURL({
    provider: 'github',
    owner: 'mcahod',
    repo: 'Taskallocation'
  });

  // Check for updates and notify
  autoUpdater.checkForUpdatesAndNotify();

  // Optional: Log update events to debug
  autoUpdater.on('checking-for-update', () => {
    console.log('Checking for update...');
  });

  autoUpdater.on('update-available', (info) => {
    console.log('Update available:', info);
  });

  autoUpdater.on('update-not-available', (info) => {
    console.log('No updates available:', info);
  });

  autoUpdater.on('error', (err) => {
    console.error('Update error:', err);
  });

  autoUpdater.on('download-progress', (progressObj) => {
    console.log(`Download speed: ${progressObj.bytesPerSecond}`);
    console.log(`Downloaded: ${progressObj.percent}%`);
  });

  autoUpdater.on('update-downloaded', (info) => {
    console.log('Update downloaded:', info);
    // Ask user to restart and install
    dialog.showMessageBox(win, {
      type: 'info',
      title: 'Update Ready',
      message: 'A new version has been downloaded. Restart now to install?',
      buttons: ['Restart', 'Later']
    }).then(result => {
      if (result.response === 0) {
        autoUpdater.quitAndInstall();
      }
    });
  });
}

app.whenReady().then(createWindow);

// Handle page navigation
ipcMain.on('navigate-to', (event, role) => {
  if (role === 'principal') {
    win.loadFile('principal/principal_dashboard.html');
  } else if (role === 'admin') {
    win.loadFile('admin/admin.html');
  } else {
    win.loadFile('staff/staff_dashboard.html');
  }
});
