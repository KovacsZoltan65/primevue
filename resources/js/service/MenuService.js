export const MenuService = {
    getMenuData(){
        return [
            {
                label: 'Home',
                items: [
                    { label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/' }
                ]
            },
            {
                label: 'UI Components',
                items: [
                    { label: 'Form Layout', icon: 'pi pi-fw pi-id-card',      to: '#' },
                    { label: 'Input',       icon: 'pi pi-fw pi-check-square', to: '#' },
                    { label: 'Button',      icon: 'pi pi-fw pi-mobile',       to: '#', class: 'rotated-icon' },
                    { label: 'Table',       icon: 'pi pi-fw pi-table',        to: '#' },
                ]
            }
        ];
    },
    getMenuItems(){
        return Promise.resolve(this.getMenuData());
    }
};