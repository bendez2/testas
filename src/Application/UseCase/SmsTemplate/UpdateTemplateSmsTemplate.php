<?php

namespace Application\UseCase\SmsTemplate;

use Application\DTO\EmailTemplate\EmailTemplateDTO;
use Application\DTO\SmsTemplate\SmsTemplateDTO;
use Application\Exception\ValidationFailedException;
use Common\Constants\ErrorCode;
use Common\Exception\BusinessException;
use Domain\EmailTemplateRepositoryInterface;
use Domain\SmsTemplateRepositoryInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class UpdateTemplateSmsTemplate
{
    #[Inject]
    protected ValidatorFactoryInterface $validationFactory;
    private SmsTemplateRepositoryInterface $smsTemplateRepository;

    public function __construct(SmsTemplateRepositoryInterface $smsTemplateRepository)
    {
        $this->smsTemplateRepository = $smsTemplateRepository;
    }

    /**
     * @param SmsTemplateDTO $smsTemplateDTO
     * @return bool
     * @throws ValidationFailedException
     */
    public function execute(SmsTemplateDTO $smsTemplateDTO): bool
    {
        $validator = $this->validationFactory->make(
            $smsTemplateDTO->getArray(),
            [
                'sender' => 'required|string',
                'text' => 'required|string',
                'serviceId' => 'required|string',
                'name' => 'required|string',
            ],
            [
                'sender.required' => 'sender is required',
                'sender.string' => 'sender is string',
                'text.required' => 'text is required',
                'text.string' => 'text is string',
                'serviceId.required' => 'serviceId is required',
                'serviceId.string' => 'serviceId is string',
                'name.required' => 'name is required',
                'name.string' => 'name is string',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationFailedException($validator->errors(), ErrorCode::INVALID_REQUEST);
        }

        return $this->smsTemplateRepository->updateTemplate($smsTemplateDTO);
    }
}