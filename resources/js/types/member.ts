import type { Paginator } from '@/types/pagination';

export interface MemberMembership {
    team: string;
    role: string;
}

export interface Member {
    id: number;
    name: string;
    email: string;
    memberships: MemberMembership[];
}

export interface PaginatedMembers extends Paginator {
    data: Member[];
}
