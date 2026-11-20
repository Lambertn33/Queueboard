import type { Paginator } from '@/types/pagination';

export interface Member {
    id: number;
    name: string;
    email: string;
}

export interface PaginatedMembers extends Paginator {
    data: Member[];
}
