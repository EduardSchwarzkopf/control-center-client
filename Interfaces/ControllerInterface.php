<?php

interface ControllerInterface
{

    public function Get(): array;
    public function Post(Request $request): array;
    public function Put(Request $request): array;
    public function Delete(Request $request): array;
}
