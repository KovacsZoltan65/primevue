export const MenuService = {
    getMenuData() {
        return [
            {
                label: "home",
                items: [
                    { label: "dashboard", icon: "pi pi-fw pi-home", to: "/dashboard", }, ],
            },
            {
                label: "sandbox",
                items: [
                    { label: "TableFilter_01", icon: "pi pi-fw pi-home", to: "/tablefilter_01", },
                ]
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
                    { label: "subdomains", icon: "", to: "/subdomains" },
                ],
            },
        ];
    },
    getMenuItems() {
        return Promise.resolve(this.getMenuData());
    },
};
