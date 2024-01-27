import {
  LayoutDashboardIcon,BorderAllIcon,
  AlertCircleIcon,
  CircleDotIcon,
  BoxMultiple1Icon,
  LoginIcon, MoodHappyIcon, ApertureIcon, UserPlusIcon,
  Category2Icon,
  ToolsKitchen2Icon,
  Discount2Icon,
  PresentationAnalyticsIcon
} from 'vue-tabler-icons';

export interface menu {
  header?: string;
  title?: string;
  icon?: any;
  to?: string;
  chip?: string;
  BgColor?: string;
  chipBgColor?: string;
  chipColor?: string;
  chipVariant?: string;
  chipIcon?: string;
  children?: menu[];
  disabled?: boolean;
  type?: string;
  subCaption?: string;
}

const sidebarItem: menu[] = [
  // { header: 'Home' },
  // {
  //   title: 'Dashboard',
  //   icon: LayoutDashboardIcon,
  //   BgColor: 'primary',
  //   to: '/'
  // },
  {
    title: 'Menu Overview',
    icon: PresentationAnalyticsIcon,
    BgColor: 'primary',
    to: '/'
  },

  { header: 'Menu Management' },

  {
    title: "Categories",
    icon: Category2Icon,
    BgColor: 'primary',
    to: "/categories",
  },
  {
    title: "Products",
    icon: ToolsKitchen2Icon,
    BgColor: 'primary',
    to: "/products",
  },

  { header: 'Promotions' },

  {
    title: "Discounts",
    icon: Discount2Icon,
    BgColor: 'primary',
    to: "/discounts",
  },

  // { header: 'Ui components' },
  // {
  //   title: "Alert",
  //   icon: AlertCircleIcon,
  //   BgColor: 'primary',
  //   to: "/ui/alerts",
  // },
  // {
  //   title: "Button",
  //   icon: CircleDotIcon,
  //   BgColor: 'primary',
  //   to: "/ui/buttons",
  // },
  // {
  //   title: "Cards",
  //   icon: BoxMultiple1Icon,
  //   BgColor: 'primary',
  //   to: "/ui/cards",
  // },
  // {
  //   title: "Tables",
  //   icon: BorderAllIcon,
  //   BgColor: 'primary',
  //   to: "/ui/tables",
  // },

  // { header: 'Auth' },
  // {
  //   title: 'Login',
  //   icon: LoginIcon,
  //   BgColor: 'primary',
  //   to: '/auth/login'
  // },
  // {
  //     title: 'Register',
  //     icon: UserPlusIcon,
  //     BgColor: 'primary',
  //     to: '/auth/register'
  // },
  // { header: 'Extra' },
  // {
  //     title: 'Icons',
  //     icon: MoodHappyIcon,
  //     BgColor: 'primary',
  //     to: '/icons'
  // },
  // {
  //     title: 'Sample Page',
  //     icon: ApertureIcon,
  //     BgColor: 'primary',
  //     to: '/sample-page'
  // },

];

export default sidebarItem;
