// types/index.ts

interface PaginationLinks {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
}

export interface PaginationMeta {
    current_page: number;
    from: number;
    last_page: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    path: string;
    per_page: number;
    to: number;
    total: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    links: PaginationLinks;
    meta: PaginationMeta;
}

export interface Location {
    country: string;
    province: string;
    street: string;
}

export interface Photo {
    thumb: string;
    search: string;
    full: string;
}

export interface Property {
    id: number;
    title: string;
    description: string;
    for_sale: boolean;
    for_rent: boolean;
    sold: boolean;
    price: string;
    currency: string | null;
    currency_symbol: string;
    property_type: string;
    bedrooms: number;
    bathrooms: number;
    area: number;
    area_type: string;
    created_at: string;
    location: Location;
    photo: Photo;
}

export interface HomeProps {
  searchParams: Promise<{ [key: string]: string | string[] | undefined }>
}

export interface ErrorResponse {
  message: string;
}
