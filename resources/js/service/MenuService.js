export const MenuService = {
    getMenuData(){
        return [
            {
                label: 'home',
                items: [
                    { label: 'dashboard', icon: 'pi pi-fw pi-home', to: '/dashboard' }
                ]
            },
            {
                label: 'administration',
                items: [
                    { label: 'users', icon: 'pi pi-fw pi-users', to: '#'},
                    { label: 'roles', icon: '', to: '#'},
                    { label: 'permissions', icon: '', to: '#'},
                ]
            },
            {
                label: 'specimens',
                items: [
                    { label: 'companies', 'icon': 'pi pi-fw pi-briefcase', to: '/companies' }
                ]
            }
            /*
            {
                label: 'UI Components',
                items: [
                    { label: 'Form Layout', icon: 'pi pi-fw pi-id-card',      to: '#' },
                    { label: 'Input',       icon: 'pi pi-fw pi-check-square', to: '#' },
                    { label: 'Button',      icon: 'pi pi-fw pi-mobile',       to: '#', class: 'rotated-icon' },
                    { label: 'Table',       icon: 'pi pi-fw pi-table',        to: '#' },
                ]
            }
            */
        ];
    },
    getMenuItems(){
        return Promise.resolve(this.getMenuData());
    }
};