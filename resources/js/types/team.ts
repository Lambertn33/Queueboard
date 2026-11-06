import type { Paginator } from '@/types/pagination';

export interface Team {
    id: number;
    name: string;
    members_count: number;
    projects_count: number;
}

export interface PaginatedTeams extends Paginator {
    data: Team[];
}
