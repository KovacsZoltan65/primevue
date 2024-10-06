export const MenuService = {
    getMenuData() {
        return [
            {
                label: "home",
                items: [
                    { label: "dashboard", icon: "pi pi-fw pi-home", to: "/dashboard", },
                ],
            },

            {
                label: "administration",
                items: [
                    { label: "users", icon: "pi pi-fw pi-users", to: "/users" },
                    { label: "roles", icon: "pi pi-fw pi-users", to: "/roles" },
                    { label: "permissions", icon: "pi pi-fw pi-users", to: "/permissions", },
                ],
            },
            {
                label: "system",
                items: [
                    {
                        label: "geo",
                        items: [
                            { label: "countries", icon: "pi pi-fw pi-map-marker", to: "/countries", },
                            { label: "regions", icon: "pi pi-fw pi-map-marker", to: "/regions", },
                            { label: "cities", icon: "pi pi-fw pi-map-marker", to: "/cities", },
                        ],
                    },
                    { label: "subdomain_states", icon: "", to: "/subdomain_states", },
                ],
            },
            {
                label: "specimens",
                items: [
                    { label: "companies", icon: "pi pi-fw pi-briefcase", to: "/companies", },
                    { label: "subdomains", icon: "", to: "/subdomains", },
                ],
            },
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
    getMenuItems() {
        return Promise.resolve(this.getMenuData());
    },
};
