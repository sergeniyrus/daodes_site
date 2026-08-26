#!/usr/bin/env python3
import subprocess
import gi
gi.require_version('Gtk', '3.0')
gi.require_version('AyatanaAppIndicator3', '0.1')
from gi.repository import Gtk, AyatanaAppIndicator3 as AppIndicator3, GLib

SERVICES = ['nginx', 'mariadb', 'ipfs', 'ollama']

SERVICE_ACTIONS = {
    'nginx': ['start', 'stop', 'reload'],
    'mariadb': ['start', 'stop', 'restart'],
    'ipfs': ['start', 'stop', 'restart'],
    'ollama': ['start', 'stop', 'restart']
}

class ServiceMonitor:
    def __init__(self):
        self.indicator = AppIndicator3.Indicator.new(
            "custom-service-monitor",
            "utilities-system-monitor",
            AppIndicator3.IndicatorCategory.APPLICATION_STATUS
        )
        self.indicator.set_status(AppIndicator3.IndicatorStatus.ACTIVE)
        self.update_menu()

    def get_service_status(self, service_name):
        try:
            result = subprocess.run(
                ['systemctl', 'is-active', service_name],
                stdout=subprocess.PIPE,
                stderr=subprocess.PIPE,
                text=True
            )
            status = result.stdout.strip()
            if status == 'active':
                return '🟢 Работает'
            elif status == 'inactive':
                return '⚪ Остановлен'
            elif status == 'failed':
                return '🔴 Ошибка'
            else:
                return '⚫ Неизвестно'
        except Exception:
            return '⚫ Неизвестно'

    def execute_action(self, widget, service, action):
        cmd = ['pkexec', 'service', service, action]
        subprocess.Popen(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
        GLib.timeout_add(2000, self.update_menu)

    def update_menu(self, *args):
        menu = Gtk.Menu()

        header = Gtk.MenuItem(label="Монитор сервисов (MX Linux)")
        header.set_sensitive(False)
        menu.append(header)
        menu.append(Gtk.SeparatorMenuItem())

        for service in SERVICES:
            status_text = self.get_service_status(service)
            service_item = Gtk.MenuItem(label=f"{service.upper()} [{status_text}]")
            service_item.set_sensitive(False)
            menu.append(service_item)

            submenu = Gtk.Menu()
            actions = SERVICE_ACTIONS.get(service, ['start', 'stop', 'restart'])
            for action in actions:
                action_item = Gtk.MenuItem(label=action.capitalize())
                action_item.connect('activate', self.execute_action, service, action)
                submenu.append(action_item)
            
            service_item.set_submenu(submenu)
            menu.append(Gtk.SeparatorMenuItem())

        refresh_item = Gtk.MenuItem(label="🔄 Обновить статус")
        refresh_item.connect('activate', self.update_menu)
        menu.append(refresh_item)

        quit_item = Gtk.MenuItem(label="❌ Выход")
        quit_item.connect('activate', Gtk.main_quit)
        menu.append(quit_item)

        menu.show_all()
        self.indicator.set_menu(menu)
        return False 

if __name__ == "__main__":
    app = ServiceMonitor()
    Gtk.main()
